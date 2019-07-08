<?php
use Controller\FrontController;
// Use library debugger
use Tracy\Debugger;

// Loads Composer autoload
require_once  '../vendor/autoload.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

Debugger::enable();

$frontController = new FrontController();
$frontController->run();
