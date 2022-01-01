<?php

namespace App\Controller\Permission;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Permission;

final class Update
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $permission = Permission::find($body['id']);
        $permission->name = $body['name'];
        if($body['state'] !== null){
            $permission->is_active = $body['state'];
        }
        // $permission->updatingCustom();
        $permission->save();

        $res = [
            'data' => [
                'permission' => $permission,
                'body' => $body,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}