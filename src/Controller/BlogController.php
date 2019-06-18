<?php
namespace Controller;

use Model\CommentManager;
use Model\Post;
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
        $post = new PostManager();
        $allPosts = $post->getPosts();

        return $this->render('blog.twig', array('posts' => $allPosts));
    }

    /**
     * @return \Twig\Environment
     */
    public function readAction()
    {
        $id = $_GET['id'];
        $post = new PostManager();
        $onePost = $post->getPost($id);
        $comments = new CommentManager();
        $allComments = $comments->getComments($id);

        return $this->render('post.twig', array('post' => $onePost, 'comment'=> $allComments));
    }
}
