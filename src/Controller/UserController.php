<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

use App\Serializer\JsonResponse;

use App\Request\Pagination;

class UserController
{
    use JsonResponse;

    public function list(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $getParsedBody = (array) $request->getParsedBody();

        $data = [
            'getParsedBody' => $getParsedBody,
        ];

        return $this->response($response, 200, $data);
    }
}
