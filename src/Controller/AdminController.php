<?php

namespace App\Controller;

use App\Model\CommentManager;
use App\Model\PostManager;
use App\Model\UserManager;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class AdminController
 * @package App\Controller
 */
class AdminController extends Controller
{
    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function indexAction()
    {
        return $this->adminAction(0, false);
    }

    /**
     * @param int $show
     * @param $confirm
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
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
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
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
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
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
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function updateAction()
    {
        $data['idy'] = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $data['author'] = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_SPECIAL_CHARS);
        $data['title'] = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $data['headline'] = filter_input(INPUT_POST, 'lead', FILTER_SANITIZE_SPECIAL_CHARS);
        $data['content'] = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!empty($data['idy']) && !empty($data['author']) && !empty($data['title']) && !empty($data['headline']) && !empty($data['content']) && $this->session->checkAdmin()) {
            (new postManager)->updatePost($data);

            return $this->adminAction(0, false);
        }
        return $this->render('home.twig');
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function addAction()
    {
        $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_SPECIAL_CHARS);
        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
        $headline = filter_input(INPUT_POST, 'headline', FILTER_SANITIZE_SPECIAL_CHARS);
        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_SPECIAL_CHARS);

        if (!empty($author) && !empty($title) && !empty($headline) && !empty($content) && $this->session->checkAdmin()) {
            (new postManager)->addPost($title, $author, $headline, $content);

            return $this->adminAction(0, false);
        }
        return $this->render('home.twig');
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function deleteAction()
    {
        $idy = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $table = filter_input(INPUT_GET, 'table', FILTER_SANITIZE_STRING);

        if (!empty($idy) && !empty($table) && $this->session->checkAdmin()) {
            switch ($table) {
                case 'post' :
                    (new commentManager)->deleteComment($idy, 'posts_id');
                    (new postManager)->deletePost($idy);
                    $show = 0;
                    break;
                case 'comment' :
                    (new commentManager)->deleteComment($idy, 'id');
                    $show = 1;
                    break;
                case 'user' :
                    (new CommentManager())->deleteComment($idy, 'users_id');
                    (new UserManager())->deleteUser($idy);
                    $show = 2;
                    break;
            }
            Return $this->adminAction($show, false);
        }
        return $this->render('home.twig');
    }

    /**
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
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
