<?php
use Controller\FrontController;
// Use library debugger
use Tracy\Debugger;

// Loads Composer autoload
require_once dirname(__DIR__) . '/vendor/autoload.php';

Debugger::enable();

$frontController = new FrontController();
$frontController->run();
