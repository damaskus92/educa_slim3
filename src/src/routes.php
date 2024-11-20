<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {
    $container = $app->getContainer();

    $app->get('/', function (Request $request, Response $response, array $args) use ($container) {
        return $this->view->render($response, 'home.twig', []);
    })->setName('home');

    $app->get('/schools', function (Request $request, Response $response, array $args) use ($container) {
        return $this->view->render($response, 'school.twig', []);
    })->setName('schools');

    $app->get('/students', function (Request $request, Response $response, array $args) use ($container) {
        return $this->view->render($response, 'student.twig', []);
    })->setName('students');
};
