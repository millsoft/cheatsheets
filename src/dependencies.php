<?php
// DIC configuration
$container = $app->getContainer();
// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// TWIG View
$container['view'] = function ($c) {
    $settings = $c->get('settings')['renderer'];

    $view = new \Slim\Views\Twig($settings['template_path'], [
        'cache' => false
    ]);
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $c->get('request')->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($c->get('router'), $basePath));

    return $view;
};


// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};


$container['db'] = function($c){
    //$config = json_decode(file_get_contents($config_file));

    $settings = $c->get("settings");
    $db_settings = $settings['database'];

    $db = \ParagonIE\EasyDB\Factory::create(
        "mysql:host={$db_settings['server']};dbname={$db_settings['database']}",
        $db_settings['username'],
        $db_settings['password']
    );
    return $db;
};

