<?php


namespace Controller;

class RegisterController extends FrontController
{
    public function indexAction(){
        return $this->twig->render('register.twig');

    }
}