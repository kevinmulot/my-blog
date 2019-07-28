<?php

namespace App\Controller;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class Controller
 * @package App\Controller
 */
abstract Class Controller
{
    /**
     * @var Environment
     */
    protected $twig;
    /**
     * @var SessionController
     */
    protected $session;

    /**
     * Controller constructor
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
        $this->session = new SessionController();
    }

    /**
     * @param $view
     * @param array $params
     * @return string
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function render($view, array $params = [])
    {
        return $this->twig->render($view, $params);
    }

    /**
     * @param $msg
     */
    public function alert($msg)
    {
        $alert = "<script>alert('$msg');</script>";
        echo filter_var($alert);
    }
}
