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
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

//API
$container['api'] = function($c) {
    $api = $c->get('settings')['api'];
    $api['api_url'] = $api['base_url'] . '/api/' . $api['version'];
    return $api;
};

// Eloquent!
// thanks to https://stackoverflow.com/questions/38256812/call-to-a-member-function-connection-on-null-error-in-slim-using-laravels-elo
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule) {
    return $capsule;
};

$container['task'] = function($c) {
    return new App\Model\Task();
};

$container['subtask'] = function($c) {
    return new App\Model\Subtask();
};

$container['errorHandler'] = function ($c) {
    return function ($request,$response,$exception) use ($c) {
        $data = [
            'status' => 'error',
            'code'  => $exception->getCode(),
            'message' => $exception->getMessage(),
        ];
        return $response->withJson($data,$exception->getCode());
    };
};