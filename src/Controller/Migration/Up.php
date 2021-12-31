<?php

namespace App\Controller\Migration;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Database\Migrations\User;

final class Up
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        User::up();

        return $this->response($response, 200, []);
    }
}