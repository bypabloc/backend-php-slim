<?php

namespace App\Controller\Permission;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Permission;

final class State
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $permission = Permission::find($body['id']);
        $permission->is_active = $body['is_active'];
        // $permission->updatingCustom();
        $permission->save();

        $res = [
            'data' => [
                'permission' => $permission,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}