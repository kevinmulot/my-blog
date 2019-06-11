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
    private $twig;

    /**
     * Controller constructor
     * @param Environment $twig
     */
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }
}