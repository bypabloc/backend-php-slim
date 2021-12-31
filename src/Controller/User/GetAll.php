<?php

namespace App\Controller\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Model\User;

final class GetAll
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $getParsedBody = (array) $request->getParsedBody();
    
            $password = '12345678';
    
            $data = [
                'getParsedBody' => $getParsedBody,
                'users' => User::all(),
            ];
        } catch (\Throwable $th) {
            return $this->response($response, 500, $th);
        }

        return $this->response($response, 200, $data);
    }
}