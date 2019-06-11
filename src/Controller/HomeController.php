<?php


namespace Controller;


use \Model\Post;

class HomeController extends FrontController
{
 public function indexAction(){

    return $this->twig->render('home.twig');

 }
}