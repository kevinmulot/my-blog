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
        $comments = (new commentManager)->getComments($id);

        return $this->render('post.twig', array('post' => $post, 'comment' => $comments));
    }

    /**
     * @return \Twig\Environment
     */
    public function adminAction()
    {
        $posts = (new PostManager)->getPosts();
        $comments = (new CommentManager())->getAllComments();
        $show = 0;
        return $this->render('admin.twig', array('posts' => $posts, 'comments' => $comments, 'show' => $show));
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

    /**
     * @return \Twig\Environment
     */
    public function updateAction()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_SPECIAL_CHARS);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $lead = filter_input(INPUT_POST, 'lead', FILTER_SANITIZE_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);

        $postManager = (new postManager);
        $postManager->updatePost($title, $author, $lead, $content, $id);
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
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $postManager = (new postManager);
        $commentManager = (new commentManager);
        $commentManager->deleteComment($id);
        $postManager->deletePost($id);

        return $this->adminAction();
    }

    /**
     * @return \Twig\Environment
     */
    public function removeAction()
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $commentManager = (new commentManager);
        $commentManager->deleteComment($id);
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
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $commentManager = (new commentManager);
        $commentManager->validate($id);
        $comments = $commentManager->getAllComments();
        $posts = (new PostManager)->getPosts();
        $show = 1;

        return $this->render('admin.twig', array('posts' => $posts, 'comments' => $comments, 'show' => $show));
    }
}
