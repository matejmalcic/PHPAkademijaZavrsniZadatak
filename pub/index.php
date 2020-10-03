<?php

define('BP', dirname(__DIR__));
define('URL', 'http://phpacademy.inchoo.io/~polaznik20/');

spl_autoload_register(function ($class) {
    $class = lcfirst($class);
    $filename = BP . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

    if (file_exists($filename)) {
        require_once $filename;
    }
});

session_start();

$router = new \App\Core\Router();
$application = new \App\Core\Application($router);

$application->run();