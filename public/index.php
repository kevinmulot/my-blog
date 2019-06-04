<?php
require '../vendor/autoload.php';
require_once '../src/controllers/Router.php';
require_once '../src/models/Manager.php';

//template view
$loader = new Twig_Loader_Filesystem('../src/view');
$twig = new Twig_Environment($loader, [
    'cache' => false /* '../tmp',*/
]);


