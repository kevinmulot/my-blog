<?php

namespace Controller;

use Twig\Environment;

/**
 * Class Controller
 * @package Controller
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
        $this->session  = new SessionController();
    }

    /**
     * @return Environment
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
