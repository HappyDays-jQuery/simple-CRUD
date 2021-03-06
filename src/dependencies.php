<?php
// DIC configuration

$container = $app->getContainer();

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

// migration
$container['migration'] = function () {
    return new Phpmig\Adapter\File\Flat(__DIR__ . '/../migrations/.migrations.log');
};

// validator
$container['validator::ticket:store'] = function ($container) {
    return new DavidePastore\Slim\Validation\Validation($container->get('rule::tickets:store'));
};
$container['validator::ticket:delete'] = function ($container) {
    return new DavidePastore\Slim\Validation\Validation($container->get('rule::tickets:delete'));
};
$container['validator::ticket:update'] = function ($container) {
    return new DavidePastore\Slim\Validation\Validation($container->get('rule::tickets:update'));
};

// validation
$container['rule::tickets:store'] = function () {
    return require __DIR__ . '/../rules/tickets/store.php';
};
$container['rule::tickets:delete'] = function () {
    return require __DIR__ . '/../rules/tickets/delete.php';
};
$container['rule::tickets:update'] = function () {
    return require __DIR__ . '/../rules/tickets/update.php';
};
