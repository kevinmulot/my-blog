<?php

namespace Controller;

use Model\CommentManager;
use Model\PostManager;

session_start();

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
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $post = (new PostManager)->getPost($id);
        $comments = (new CommentManager)->getComments($id);

        return $this->render('post.twig', array('post' => $post, 'comment' => $comments));
    }

    /**
     * @return \Twig\Environment
     */
    public function adminAction()
    {
        $posts = (new PostManager)->getPosts();
        $comments = (new CommentManager())->getAllComments();

        return $this->render('admin.twig', array('posts' => $posts, 'comments' => $comments));
    }

    /**
     * @return \Twig\Environment
     */
    public function editAction()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $post = (new PostManager)->getPost($id);

        return $this->render('edit.twig', array('post' => $post));
    }
    public function updateAction()
    {

    }
}
