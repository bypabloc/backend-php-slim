<?php

namespace App\Controller\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\User;

final class Create
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $params = (array) $request->getParsedBody() ?: [];
        $body = $params['data'] ?? [];

        $user = User::Create([
            'nickname' => $body['nickname'],
            'email' => $body['email'],
            'password' => Hash::make($body['password']),
        ]);
        
        $data = [
            'getParsedBody' => $body,
            'user' => $user,
        ];

        return $this->response($response, 200, $data);
    }
}