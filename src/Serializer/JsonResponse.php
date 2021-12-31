<?php

declare(strict_types=1);

namespace App\Serializer;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

trait JsonResponse
{
    public function response(
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