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

use \App\Services\Logger;

try {

    $containerBuilder = new ContainerBuilder();
    $container = $containerBuilder->build();

    $settings = new Settings();
    $settings();

    AppFactory::setContainer($container);
    $app = AppFactory::create();
    
    $app->add(\App\Middleware\CorsMiddleware::class);

    $app->addRoutingMiddleware();
    $app->addBodyParsingMiddleware();

    $errorMiddleware = $app->addErrorMiddleware(true, true, true);

    Logger::info(
        message: 'App started',
    );

} catch (\Throwable $th) {
    echo $th->getMessage();
}

require __DIR__ . '/Routes.php';

require __DIR__ . '/../Config/bootstrap.php';