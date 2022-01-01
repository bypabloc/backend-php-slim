<?php

declare(strict_types=1);

use Slim\Routing\RouteCollectorProxy;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Controller\User;
use App\Controller\Migration;
use App\Controller\Auth;

use App\Middleware\Token;

// $app->get('/', 'App\Controller\DefaultController:getHelp');
// $app->get('/status', 'App\Controller\DefaultController:getStatus');
// $app->post('/login', \App\Controller\User\Login::class);

// $app->add( new \App\Middleware\JsonBodyParserMiddleware() );

$app->get('/migrations', \App\Controller\Migrations::class);

$app->group('/migrations', function (RouteCollectorProxy $app) {
    $app->get('/up', Migration\Up::class);
    $app->get('/down', Migration\Down::class);
});

$app->get('/test', function ($request, $response, array $args) {
    $response->getBody()->write('Prueba');
    
    return $response;
});

$app->group('/api/v1', function (RouteCollectorProxy $app) {

    $app->group('/users', function (RouteCollectorProxy $app) {

        $app->get('/get_all', User\GetAll::class)->add(new \App\Middleware\Pagination());
        $app->post('/create', User\Create::class)->add(new \App\Middleware\Validation\User\Create());

    });

    $app->group('/auth', function (RouteCollectorProxy $app) {

        $app->post('/sign_up', Auth\SignUp::class)->add(new \App\Middleware\Validation\Auth\SignUp());
        $app->post('/sign_in', Auth\SignIn::class)->add(new \App\Middleware\Validation\Auth\SignIn());
        $app->post('/sign_out', Auth\SignOut::class)->add(Token::class);

    });
});
