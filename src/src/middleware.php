<?php

use Slim\App;

return function (App $app) {
    // e.g: $app->add(new \Slim\Csrf\Guard);

    $app->add(function ($request, $response, $next) {
        $this->view->addExtension(new Knlv\Slim\Views\TwigMessages($this->flash));

        return $next($request, $response);
    });
};
