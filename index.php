<?php

#use \Psr\Http\Message\ServerRequestInterface as Request;
#use \Psr\Http\Message\ResponseInterface as Response;

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

require_once(__DIR__ . "/vendor/autoload.php");
session_start();

$settings = require __DIR__ . '/src/config.php';
$app = new \Slim\App($settings);

require_once __DIR__ . "/src/dependencies.php";
require_once __DIR__ . "/src/middleware.php";
require_once __DIR__ . "/src/routes.php";

$app->run();