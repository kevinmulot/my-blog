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
     * @param $show
     * @param $confirm
     * @return \Twig\Environment
     */
    public function readAll($show, $confirm)
    {
        $posts = (new PostManager())->getPosts();
        $comments = (new CommentManager())->getAllComments();
        $user = (new UserManager())->getAllUsers();

        return $this->render('admin.twig', array('posts' => $posts, 'comments' => $comments, 'show' => $show, 'user' => $user, 'confirm' => $confirm));
    }

    /**
     * @return \Twig\Environment
     */
    public function readAction()
    {
        $idy = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $page = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT);
        $post = (new PostManager)->getPost($idy);
        $commentManager = (new commentManager);
        $comments = $commentManager->getComments($idy);
        if ($this->session->isLogged()) {
            $wcomments = $commentManager->getWaitingComments($idy, filter_var($_SESSION['user']['id']));
            if ($wcomments != false) {
                return $this->render('post.twig', array('post' => $post, 'comment' => $comments, 'wcomment' => $wcomments, 'p' => $page));
            }
        }
        return $this->render('post.twig', array('post' => $post, 'comment' => $comments, 'p' => $page));
    }

    /**
     * @return \Twig\Environment
     */
    public function adminAction()
    {
        if ($this->session->isLogged()) {

            if (filter_var($_SESSION['user']['status']) == true) {
                return $this->readAll(0, false);
            }
            $this->session->destroySession();
        }
        return $this->render('home.twig');
    }

    /**
     * @return \Twig\Environment
     */
    public function editAction()
    {
        $idy = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $post = (new PostManager)->getPost($idy);

        return $this->render('edit.twig', array('post' => $post));
    }

    /**
     * @return \Twig\Environment
     */
    public function updateAction()
    {
        $idy = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_SPECIAL_CHARS);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $lead = filter_input(INPUT_POST, 'lead', FILTER_SANITIZE_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);
        $postManager = (new postManager);
        $postManager->updatePost($title, $author, $lead, $content, $idy);

        return $this->adminAction();
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
        $postManager = (new postManager);
        $postManager->addPost($title, $author, $lead, $content);

        return $this->adminAction();
    }

    /**
     * @return \Twig\Environment
     */
    public function commentAction()
    {
        $posts_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

        if ($this->session->isLogged()) {
            $content = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS);
            $idy = filter_var($_SESSION['user']['id']);
            $author = filter_var($_SESSION['user']['username']);
            $commentManager = (new commentManager);
            $commentManager->addComment($author, $content, $posts_id, $idy);
            $post = (new PostManager)->getPost($posts_id);
            $comments = (new commentManager)->getComments($posts_id);
            $wcomments = $commentManager->getWaitingComments($posts_id, $idy);

            if ($wcomments != false) {

                return $this->render('post.twig', array('post' => $post, 'comment' => $comments, 'wcomment' => $wcomments));
            }

            return $this->render('post.twig', array('post' => $post, 'comment' => $comments));
        }
        $error = 'Veuillez vous connecter !';
        $post = (new PostManager)->getPost($posts_id);
        $comments = (new commentManager)->getComments($posts_id);

        return $this->render('post.twig', array('post' => $post, 'comment' => $comments, 'error' => $error,));
    }

    /**
     * @return \Twig\Environment
     */
    public function deleteAction()
    {
        $idy = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $postManager = (new postManager);
        $commentManager = (new commentManager);
        $commentManager->deletePostComments($idy);
        $postManager->deletePost($idy);

        return $this->adminAction();
    }

    /**
     * @return \Twig\Environment
     */
    public function removeAction()
    {
        $idy = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $commentManager = (new commentManager);
        $commentManager->deleteComment($idy);

        Return $this->readAll(1, false);
    }

    /**
     * @return \Twig\Environment
     */
    public function deleteUserAction()
    {
        $idy = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $userManager = (new UserManager());
        (new CommentManager())->deleteUserComments($idy);
        $userManager->deleteUser($idy);

        return $this->readAll(2, false);
    }

    /**
     * @return \Twig\Environment
     */
    public function validateAction()
    {
        $idy = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $commentManager = (new commentManager);
        $commentManager->validate($idy);

        return $this->readAll(1, false);
    }

    /**
     * @return \Twig\Environment
     */
    public function confirmAction()
    {
        $idy = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $table = filter_input(INPUT_GET, 'table', FILTER_SANITIZE_STRING);
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
        return $this->readAll($show, $confirm);
    }
}
