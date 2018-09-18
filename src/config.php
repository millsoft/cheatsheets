<?php


$config = [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header
        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../ui/',
        ],
        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => isset($_ENV['docker']) ? 'php://stdout' : __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        "database" => [
            "server" => "",
            "username" => "",
            "password" => "",
            "database" => ""
        ]
    ],
];


$userconfig_file = __DIR__ . "/userconfig.php";
if(file_exists($userconfig_file)){
    $userconfig = require_once($userconfig_file);
    $config['settings'] = array_merge($config['settings'], $userconfig);
}

return $config;