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
        $body = $request->getAttribute('body');

        $user = new User();
        $user->nickname = $body['nickname'];
        $user->email = $body['email'];
        $user->sex = $body['sex'];
        $user->birthday = $body['birthday'];
        $user->password = $body['password'];

        $user->creatingCustom();

        $user->save();

        $user->createdCustom(); //Revisar****

        $data = [
            'user' => [
                'nickname' => $user->nickname,
                'email' => $user->email,
                'token' => $user->token,
            ],
        ];

        $res = [
            'data' => $user,
        ];

        return $this->response($response, 200, $res);
    }
}