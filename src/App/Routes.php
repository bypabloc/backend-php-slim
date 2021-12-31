<?php

declare(strict_types=1);

use Slim\Routing\RouteCollectorProxy;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Controller\UserController;

// $app->get('/', 'App\Controller\DefaultController:getHelp');
// $app->get('/status', 'App\Controller\DefaultController:getStatus');
// $app->post('/login', \App\Controller\User\Login::class);

$app->get('/hello/{name}', function ($request, $response, array $args) {
    $response->getBody()->write($args['name']);
    
    return $response;
});

$app->group('/api/v1', function (RouteCollectorProxy $app) {
    $app->group('/users', function (RouteCollectorProxy $app) {
        $app->get('/list', [UserController::class, 'list'])->add(new \App\Middleware\Pagination());
        // $app->get('/list', function (Request $request, Response $response, array $args) {
        //     // Route for /billing
        //     $response->getBody()->write('11111111');
    
        //     return $response;
        // });
        // $app->get('/2', function (Request $request, Response $response, array $args) {
        //     // Route for /billing
        //     $response->getBody()->write('222222222');
    
        //     return $response;
        // });
    });
});

// $app->group('/api/v1', function () use ($app): void {
//     $app->group('/users', function () use ($app): void {
//         $app->get('', function ($request, $response, array $args) {
//             $response->getBody()->write('prueba');
            
//             return $response;
//         });
//     });
// });
