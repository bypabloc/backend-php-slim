<?php

namespace App\Controller\MyProfile;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\User;

final class ChangePassword
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $user = $session->user();
        if(!Hash::validate($body['password_current'], $user->password)){
            return $this->response($response, 422, [
                'errors' => [
                    'user' => ['Credentials are incorrect.'],
                ],
            ]);
        }
        
        $user->password = $body['password'];

        $user->updatingCustom();

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