<?php

use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['renderer'] = function ($c) {
        $settings = $c->get('settings')['renderer'];
        return new \Slim\Views\PhpRenderer($settings['template_path']);
    };

    // monolog
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };

    // database
    $container['db'] = function ($c) {
        return new \Medoo\Medoo([
            'database_type' => 'mysql',
            'server' => 'mysql',
            'database_name' => 'educa_db',
            'username' => 'root',
            'password' => 'secret'
        ]);
    };

    $container['view'] = function ($c) {
        $view = new \Slim\Views\Twig('../templates', [
            'cache' => false
        ]);

        // Instantiate and add Slim specific extension
        $router = $c->get('router');
        $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
        $view->addExtension(new Slim\Views\TwigExtension($router, $uri));

        return $view;
    };

    $container['flash'] = function () {
        return new \Slim\Flash\Messages();
    };
};
