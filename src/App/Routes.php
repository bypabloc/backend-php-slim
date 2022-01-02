<?php

declare(strict_types=1);

use Slim\Routing\RouteCollectorProxy;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Controller\User;
use App\Controller\Role;
use App\Controller\Migration;
use App\Controller\Auth;
use App\Controller\Permission;

use App\Middleware\Token;
use App\Middleware\BodyParser;

$app->group('/migrations', function (RouteCollectorProxy $app) {
    $app->get('/up', Migration\Up::class);
    $app->get('/down', Migration\Down::class);
});

$app->get('/test', function ($request, $response, array $args) {
    $response->getBody()->write('Prueba');
    
    return $response;
});

$app->group('/api/v1', function (RouteCollectorProxy $app) {

    $app->group('/auth', function (RouteCollectorProxy $app) {

        $app->post('/sign_up', Auth\SignUp::class)->add(new \App\Middleware\Validation\Auth\SignUp());
        $app->post('/sign_in', Auth\SignIn::class)->add(new \App\Middleware\Validation\Auth\SignIn());
        $app->post('/sign_out', Auth\SignOut::class)->add(Token::class);

    });

    $app->get('/buyer', function ($request, $response, array $args) {
        $response->getBody()->write('Buyer');
        
        return $response;
    });

    $app->group('/users', function (RouteCollectorProxy $app) {

        $app->get('/get_all', User\GetAll::class)->add(new \App\Middleware\Pagination());
        $app->post('/create', User\Create::class)->add(new \App\Middleware\Validation\User\Create());

    })->add(Token::class);

    $app->group('/roles', function (RouteCollectorProxy $app) {
        
        $app->get('/get_all', Role\GetAll::class)->add(new \App\Middleware\Pagination());
        $app->get('/find', Role\Find::class)->add(new \App\Middleware\Validation\Role\Find());
        $app->post('/create', Role\Create::class)->add(new \App\Middleware\Validation\Role\Create());
        $app->post('/update', Role\Update::class)->add(new \App\Middleware\Validation\Role\Update());
        $app->post('/state', Role\State::class)->add(new \App\Middleware\Validation\Role\State());

        $app->post('/assign_permission', Role\AssignPermission::class)->add(new \App\Middleware\Validation\Role\AssignPermission());
        $app->post('/assign_permissions', Role\AssignPermissions::class)->add(new \App\Middleware\Validation\Role\AssignPermissions());

    })->add(Token::class);

    $app->group('/permissions', function (RouteCollectorProxy $app) {

        $app->get('/get_all', Permission\GetAll::class)->add(new \App\Middleware\Pagination());
        $app->get('/find', Permission\Find::class)->add(new \App\Middleware\Validation\Permission\Find());
        $app->post('/create', Permission\Create::class)->add(new \App\Middleware\Validation\Permission\Create());
        $app->post('/update', Permission\Update::class)->add(new \App\Middleware\Validation\Permission\Update());
        $app->post('/state', Permission\State::class)->add(new \App\Middleware\Validation\Permission\State());

    })->add(Token::class);

})->add(BodyParser::class);