<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;

require __DIR__ . '/../../vendor/autoload.php';
$baseDir = __DIR__ . '/../../';

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Slim\Middleware\OutputBufferingMiddleware;
use Slim\Psr7\Factory\StreamFactory;
use DI\ContainerBuilder;
use \App\App\Settings;

try {

    $containerBuilder = new ContainerBuilder();
    $container = $containerBuilder->build();

    $settings = new Settings();
    $settings();

    AppFactory::setContainer($container);
    $app = AppFactory::create();

    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();
    $errorMiddleware = $app->addErrorMiddleware(true, true, true);

    $app->add(function ($request, $handler) {
        $response = $handler->handle($request);
        return $response
                ->withHeader('Access-Control-Allow-Origin', '*')
                ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                ->withHeader('Access-Control-Allow-Methods', 'GET, POST');
    });

    $container = $app->getContainer();

} catch (\Throwable $th) {
    echo $th->getMessage();
}

require __DIR__ . '/Routes.php';

require __DIR__ . '/../Config/bootstrap.php';