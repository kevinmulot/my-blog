<?php

namespace App\Controller;

use App\Model\CommentManager;
use App\Model\PostManager;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController extends Controller
{
    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction()
    {
        $page = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT);

        $postManager = new PostManager();
        $nbPost = $postManager->countPosts();
        $postPerPage = 3;
        $nbPage = ceil($nbPost['total'] / $postPerPage);
        if (isset($page) and $page >= 0) {
            $posts = $postManager->getPostsPerPage($page, $postPerPage);

            return $this->render('blog.twig', array('posts' => $posts, 'nbpage' => $nbPage, 'p' => $page));
        }
        $page = 1;
        $posts = $postManager->getPostsPerPage($page, $postPerPage);

        return $this->render('blog.twig', array('posts' => $posts, 'nbpage' => $nbPage, 'p' => $page));
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function commentAction()
    {
        $posts_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $content = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);
        $page = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT);

        if (!empty($posts_id) and !empty($content) and !empty($page)) {
            $idy = $this->session->getUserVar('id');
            $author = $this->session->getUserVar('username');
            $commentManager = new commentManager;
            $commentManager->addComment($author, $content, $posts_id, $idy);
            $post = (new PostManager)->getPost($posts_id);
            $comments = $commentManager->getValidatedComments($posts_id);
            $wcomments = $commentManager->getWaitingComments($posts_id, $idy);
            if ($wcomments !== false) {

                return $this->render('post.twig', array('post' => $post, 'comment' => $comments, 'wcomment' => $wcomments, 'p' => $page));
            }
            return $this->render('post.twig', array('post' => $post, 'comment' => $comments, 'p' => $page));
        }
        return $this->render('home.twig');
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function readAction()
    {
        $idy = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $page = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT);

        if (!empty($idy) and !empty($page)) {
            $post = (new PostManager)->getPost($idy);
            $commentManager = new commentManager;
            $comments = $commentManager->getValidatedComments($idy);
            if ($this->session->isLogged()) {
                $wcomments = $commentManager->getWaitingComments($idy, $this->session->getUserVar('id'));
                if ($wcomments !== false) {

                    return $this->render('post.twig', array('post' => $post, 'comment' => $comments, 'wcomment' => $wcomments, 'p' => $page));
                }
            }
            return $this->render('post.twig', array('post' => $post, 'comment' => $comments, 'p' => $page));
        }
        return $this->render('home.twig');
    }
}
