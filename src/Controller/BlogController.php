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
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function commentAction()
    {
        $posts_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $content = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_SPECIAL_CHARS);
        $page = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_NUMBER_INT);

        if (!empty($posts_id) && !empty($content) && !empty($page)) {
            $idy = $this->session->getUserVar('id');
            $author = $this->session->getUserVar('username');
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
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
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
                $wcomments = $commentManager->getWaitingComments($idy, $this->session->getUserVar('id'));
                if ($wcomments != false) {
                    return $this->render('post.twig', array('post' => $post, 'comment' => $comments, 'wcomment' => $wcomments, 'p' => $page));
                }
            }
            return $this->render('post.twig', array('post' => $post, 'comment' => $comments, 'p' => $page));
        }
        return $this->render('home.twig');
    }
}
