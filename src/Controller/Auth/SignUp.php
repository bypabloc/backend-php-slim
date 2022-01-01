<?php

namespace App\Controller\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Model\User;

final class SignUp
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $params = (array) $request->getParsedBody() ?: [];
            $body = $params['data'] ?? [];
    
            $user = new User();
            $user->nickname = $body['nickname'] ?? '';
            $user->email = $body['email'] ?? '';
            $user->password = $body['password'] ?? '';

            $user->creatingCustom();

            $user->save();

            $user->createdCustom();

            $data = [
                'user' => [
                    'nickname' => $user->nickname,
                    'email' => $user->email,
                    'token' => $user->token,
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