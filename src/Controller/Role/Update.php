<?php

namespace App\Controller\Role;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Role;

final class Update
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $role = Role::find($body['id']);
        if(isset($body['name'])){
            $role->name = $body['name'];
        }
        if(isset($body['is_active'])){
            $role->is_active = $body['is_active'];
        }
        // $role->updatingCustom();
        $role->save();

        $res = [
            'data' => [
                'role' => $role,
                'body' => $body,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}