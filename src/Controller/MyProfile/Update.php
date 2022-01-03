<?php

namespace App\Controller\MyProfile;

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

        $user = $session->user;
        if(!empty($body['nickname'])){
            $user->nickname = $body['nickname'];
        }
        if(!empty($body['email'])){
            $user->email = $body['email'];
        }
        if(!empty($body['image'])){
            if(!empty($user->image)){
                $user->deleteFile($user->image);
            }
            $user->image = $body['image'];
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