<?php

declare(strict_types=1);

use Slim\Factory\AppFactory;

require __DIR__ . '/../../vendor/autoload.php';
$baseDir = __DIR__ . '/../../';
$dotenv = Dotenv\Dotenv::createImmutable($baseDir);
$envFile = $baseDir . '.env';
if (file_exists($envFile)) {
    $dotenv->load();
}

$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER', 'DB_PASS', 'DB_PORT']);
$settings = require __DIR__ . '/Settings.php';

try {
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
}

require __DIR__ . '/Routes.php';