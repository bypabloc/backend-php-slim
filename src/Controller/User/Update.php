<?php

namespace App\Controller\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\User;

final class Update
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $user = User::find($body['id']);
        if(!empty($body['nickname'])){
            $user->nickname = $body['nickname'];
        }
        if(!empty($body['email'])){
            $user->email = $body['email'];
        }
        if(!empty($body['password'])){
            $user->password = $body['password'];
        }
        if(!empty($body['role_id'])){
            $user->role_id = $body['role_id'];
        }
        if(isset($body['is_active'])){
            $role->is_active = $body['is_active'];
        }
        
        $user->updatingCustom();

        $user->save();

        $res = [
            'data' => [
                'user' => $user,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}