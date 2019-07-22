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
        $page = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT);

        $postManager = new PostManager();
        $nbPost = $postManager->countPosts();
        $postPP = 3;
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
            if ($this->session->isLogged()){
                $wcomments = $commentManager->getWaitingComments($idy, filter_var($_SESSION['user']['id']));
                if ($wcomments != false) {
                    return $this->render('post.twig', array('post' => $post, 'comment' => $comments, 'wcomment' => $wcomments, 'p' => $page));
                }
            }
            return $this->render('post.twig', array('post' => $post, 'comment' => $comments, 'p' => $page));
        }
        return $this->render('home.twig');
    }
}
