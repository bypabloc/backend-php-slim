<?php

declare(strict_types=1);

use Slim\Routing\RouteCollectorProxy;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Controller\UserController;
use App\Controller\User;

// $app->get('/', 'App\Controller\DefaultController:getHelp');
// $app->get('/status', 'App\Controller\DefaultController:getStatus');
// $app->post('/login', \App\Controller\User\Login::class);

$app->get('/hello/{name}', function ($request, $response, array $args) {
    $response->getBody()->write($args['name']);
    
    return $response;
});

$app->group('/api/v1', function (RouteCollectorProxy $app) {
    $app->group('/users', function (RouteCollectorProxy $app) {
        $app->get('/test', function ($request, $response, array $args) {
            $response->getBody()->write('Prueba');
            
            return $response;
        });
        $app->get('/get_all', User\GetAll::class)->add(new \App\Middleware\Pagination());
    });
});
