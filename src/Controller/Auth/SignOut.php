<?php

namespace App\Controller\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\JWT;

use App\Model\User;

final class SignOut
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $params = (array) $request->getParsedBody() ?: [];

            $body = $params['data'] ?? [];

            $session = $request->getAttribute('session');
            JWT::DestroySession($session);
    
            $data = [
                'message' => [
                    'success' => 'Logout success.',
                ],
            ];
        } catch (\Throwable $th) {
            return $this->response($response, 500, [
                'errors' => [
                    'message' => $th->getMessage(),
                    'getFile' => $th->getFile(),
                    'getLine' => $th->getLine(),
                ],
            ]);
        }

        return $this->response($response, 200, $data);
    }
}