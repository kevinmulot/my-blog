<?php

// Loads Composer autoload
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Use library debugger
use Tracy\Debugger;

Debugger::enable();


use Controller\FrontController;

$frontController = new FrontController();
$frontController->run();




