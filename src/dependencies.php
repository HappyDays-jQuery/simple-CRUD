<?php
// DIC configuration

use Dotenv\Dotenv;

$container = $app->getContainer();

$dotenv = new Dotenv(__DIR__ . "/../");
$dotenv->load();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// database
$container['db'] = function () {
    $db['host'] = getenv('db.host');
    $db['name'] = getenv('db.name');
    $db['user'] = getenv('db.user');
    $db['pass'] = getenv('db.pass');
    $pdo = new PDO('mysql:host=' . $db['host'] . ';dbname=' . $db['name'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};
