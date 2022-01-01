<?php

namespace App\Controller\Migration;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Database\Migrations\User;
use App\Database\Migrations\Session;

final class Down
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        Session::down();
        User::down();

        return $this->response($response, 200, []);
    }
}