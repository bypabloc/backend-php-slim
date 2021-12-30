<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class Controller
{
    public function __construct()
    {
    }

    protected function jsonResponse(
        ResponseInterface $response,
        int $status,
        array $data,
    ): ResponseInterface {
        $response->getBody()->write(json_encode($data));
        return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus($status);
    }
}