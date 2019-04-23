<?php

require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

session_start();

// Instantiate the app
$settings = require __DIR__ . '/src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
require __DIR__ . '/src/dependencies.php';

$container = $app->getContainer();

$container['phpmig.adapter'] = $container->get('migration');
$container['phpmig.migrations'] = function () {
    return glob(__DIR__ . DIRECTORY_SEPARATOR . 'migrations/*.php');
};

return $container;
