<?php

// Loads Composer autoload
require_once dirname(__DIR__).'/vendor/autoload.php';


use Controller\FrontController;

$frontController = new FrontController();
$frontController->run();




