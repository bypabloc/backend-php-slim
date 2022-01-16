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
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $user = new User();
        $user->nickname = $body['nickname'];
        $user->email = $body['email'];
        $user->sex = $body['sex'];
        $user->birthday = $body['birthday'];
        $user->password = $body['password'];
        $user->role_id = $body['role_id'];

        $user->creatingCustom();

        $user->save();

        $res = [
            'data' => [
                'user' => [
                    'nickname' => $user->nickname,
                    'email' => $user->email,
                ],
            ],
        ];
        return $this->response($response, 200, $res);
    }
}