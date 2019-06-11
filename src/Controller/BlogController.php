<?php


namespace Controller;

use Model\Post;

class BlogController extends FrontController
{
    public function indexAction(){

        
        return $this->twig->render('blog.twig');

    }
}