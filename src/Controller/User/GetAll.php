<?php

namespace App\Controller\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

final class GetAll
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $getParsedBody = (array) $request->getParsedBody();

        $data = [
            'getParsedBody' => $getParsedBody,
        ];

        return $this->response($response, 200, $data);
    }
}