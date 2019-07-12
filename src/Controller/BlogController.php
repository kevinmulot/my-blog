<?php

namespace Controller;

use Model\CommentManager;
use Model\PostManager;

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
        $posts = (new PostManager)->getPosts();

        return $this->render('blog.twig', array('posts' => $posts));
    }

    /**
     * @return \Twig\Environment
     */
    public function readAction()
    {
        $idy = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $post = (new PostManager)->getPost($idy);
        $comments = (new commentManager)->getComments($idy);

        return $this->render('post.twig', array('post' => $post, 'comment' => $comments));
    }

    /**
     * @return \Twig\Environment
     */
    public function adminAction()
    {
        if ($this->session->isLogged()) {

            if (filter_var($_SESSION['user']['status']) == true) {
                $posts = (new PostManager())->getPosts();
                $comments = (new CommentManager())->getAllComments();
                $show = 0;

                return $this->render('admin.twig', array('posts' => $posts, 'comments' => $comments, 'show' => $show));
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
    public function deleteAction()
    {
        $idy = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $postManager = (new postManager);
        $commentManager = (new commentManager);
        $commentManager->deleteComments($idy);
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
        $comments = $commentManager->getAllComments();
        $posts = (new PostManager)->getPosts();
        $show = 1;

        return $this->render('admin.twig', array('posts' => $posts, 'comments' => $comments, 'show' => $show));
    }

    /**
     * @return \Twig\Environment
     */
    public function validateAction()
    {
        $idy = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $commentManager = (new commentManager);
        $commentManager->validate($idy);
        $comments = $commentManager->getAllComments();
        $posts = (new PostManager)->getPosts();
        $show = 1;

        return $this->render('admin.twig', array('posts' => $posts, 'comments' => $comments, 'show' => $show));
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
        }
        $post = (new PostManager)->getPost($posts_id);
        $comments = (new commentManager)->getComments($posts_id);
        return $this->render('post.twig', array('post' => $post, 'comment' => $comments));
    }

}
