<?php

namespace App\Controller\Auth;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Model\User;

use App\Services\Hash;

final class SignIn
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $params = (array) $request->getParsedBody() ?: [];
            $body = $params['data'] ?? [];

            $column = '';
            $value = '';
            if (filter_var($body['user'], FILTER_VALIDATE_EMAIL)) {
                $column = 'email';
                $value = $body['user'];
            }else{
                $column = 'nickname';
                $value = $body['user'];
            }

            $user = User::where($column, $value)->get()->first();
            if(!Hash::validate($body['password'], $user->password)){
                return $this->response($response, 422, [
                    'errors' => [
                        'user' => ['Credentials are incorrect.'],
                    ],
                ]);
            }

            $user->generateToken();

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