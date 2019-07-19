<?php

namespace Controller;

use Model\CommentManager;
use Model\PostManager;
use Model\UserManager;

/**
 * Class BlogController
 * @package Controller
 */
class BlogController extends Controller
{
    /**
     * @return \Twig\Environment
     */
    public function indexAction()
    {
        $page = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT);

        if ($page == 404) {
            return $this->adminAction(0, false);
        }
        $postManager = new PostManager();
        $nbPost = $postManager->countPosts();
        $postPP = 4;
        $nbPage = ceil($nbPost['total'] / $postPP);
        if (isset($page) && $page >= 0) {
            $posts = $postManager->getPostsPP($page, $postPP);
            return $this->render('blog.twig', array('posts' => $posts, 'nbpage' => $nbPage, 'p' => $page));
        }
        $page = 1;
        $posts = $postManager->getPostsPP($page, $postPP);

        return $this->render('blog.twig', array('posts' => $posts, 'nbpage' => $nbPage, 'p' => $page));
    }

    /**
     * @return \Twig\Environment
     */
    public function commentAction()
    {
        $posts_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $content = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS);
        $page = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT);

        if (!empty($posts_id) && !empty($content) && !empty($page)) {
            $idy = filter_var($_SESSION['user']['id']);
            $author = filter_var($_SESSION['user']['username']);
            $commentManager = new commentManager;
            $commentManager->addComment($author, $content, $posts_id, $idy);
            $post = (new PostManager)->getPost($posts_id);
            $comments = $commentManager->getComments($posts_id);
            $wcomments = $commentManager->getWaitingComments($posts_id, $idy);
            if ($wcomments != false) {
                return $this->render('post.twig', array('post' => $post, 'comment' => $comments, 'wcomment' => $wcomments, 'p' => $page));
            }
            return $this->render('post.twig', array('post' => $post, 'comment' => $comments, 'p' => $page));
        }
        return $this->render('home.twig');
    }

    /**
     * @return \Twig\Environment
     */
    public function readAction()
    {
        $idy = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $page = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT);

        if (!empty($idy) && !empty($page)) {
            $post = (new PostManager)->getPost($idy);
            $commentManager = new commentManager;
            $comments = $commentManager->getComments($idy);
            if ($this->session->isLogged()) {
                $wcomments = $commentManager->getWaitingComments($idy, filter_var($_SESSION['user']['id']));
                if ($wcomments != false) {
                    return $this->render('post.twig', array('post' => $post, 'comment' => $comments, 'wcomment' => $wcomments, 'p' => $page));
                }
            }
            return $this->render('post.twig', array('post' => $post, 'comment' => $comments, 'p' => $page));
        }
        return $this->render('home.twig');
    }

    /**
     * @param $show
     * @param $confirm
     * @return \Twig\Environment
     */
    public function adminAction(int $show, $confirm)
    {
        if ($show >= 0 && $show <= 2) {
            if ($this->session->checkAdmin()) {
                $posts = (new PostManager())->getPosts();
                $comments = (new CommentManager())->getAllComments();
                $user = (new UserManager())->getAllUsers();

                return $this->render('admin.twig', array('posts' => $posts, 'comments' => $comments, 'show' => $show, 'user' => $user, 'confirm' => $confirm));
            }
        }
        return $this->render('home.twig');
    }

    /**
     * @return \Twig\Environment
     */
    public function confirmAction()
    {
        $idy = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $table = filter_input(INPUT_GET, 'table', FILTER_SANITIZE_STRING);

        if (!empty($table) && $this->session->checkAdmin()) {
            $confirm = array('id' => $idy, 'table' => $table);
            switch ($table) {
                case 'post' :
                    $show = 0;
                    break;
                case 'comment' :
                    $show = 1;
                    break;
                case 'user' :
                    $show = 2;
                    break;
                case 'cancel' :
                    $show = filter_input(INPUT_GET, 'show', FILTER_SANITIZE_NUMBER_INT);
                    $confirm = false;
                    break;
            }
            return $this->adminAction($show, $confirm);
        }
        return $this->render('home.twig');
    }

    /**
     * @return \Twig\Environment
     */
    public function editAction()
    {
        $idy = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        if (!empty($idy) && $this->session->checkAdmin()) {
            $post = (new PostManager)->getPost($idy);
            return $this->render('edit.twig', array('post' => $post));
        }
        return $this->render('home.twig');
    }

    /**
     * @return \Twig\Environment
     */
    public function updateAction()
    {
        $data['idy'] = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $data['author'] = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_SPECIAL_CHARS);
        $data['title'] = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $data['lead'] = filter_input(INPUT_POST, 'lead', FILTER_SANITIZE_SPECIAL_CHARS);
        $data['content'] = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!empty($data['idy']) && !empty($data['author']) && !empty($data['title']) && !empty($data['lead']) && !empty($data['content']) && $this->session->checkAdmin()) {
            (new postManager)->updatePost($data);

            return $this->adminAction(0, false);
        }
        return $this->render('home.twig');
    }

    /**
     * @return \Twig\Environment
     */
    public function addAction()
    {
        $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_SPECIAL_CHARS);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $lead = filter_input(INPUT_POST, 'lead', FILTER_SANITIZE_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!empty($author) && !empty($title) && !empty($lead) && !empty($content) && $this->session->checkAdmin()) {
            (new postManager)->addPost($title, $author, $lead, $content);

            return $this->adminAction(0, false);
        }
        return $this->render('home.twig');
    }

    /**
     * @return \Twig\Environment
     */
    public function deleteAction()
    {
        $idy = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $table = filter_input(INPUT_GET, 'table', FILTER_SANITIZE_STRING);

        if (!empty($idy) && !empty($table) && $this->session->checkAdmin()) {
            switch ($table) {
                case 'post' :
                    (new commentManager)->deletePostComments($idy);
                    (new postManager)->deletePost($idy);
                    $show = 0;
                    break;
                case 'comment' :
                    (new commentManager)->deleteComment($idy);
                    $show = 1;
                    break;
                case 'user' :
                    (new CommentManager())->deleteUserComments($idy);
                    (new UserManager())->deleteUser($idy);
                    $show = 2;
                    break;
            }
            Return $this->adminAction($show, false);
        }
        return $this->render('home.twig');
    }

    /**
     * @return \Twig\Environment
     */
    public function validateAction()
    {
        $idy = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        if (!empty($idy) && $this->session->checkAdmin()) {
            (new commentManager)->validate($idy);

            return $this->adminAction(1, false);
        }
        return $this->render('home.twig');
    }
}
