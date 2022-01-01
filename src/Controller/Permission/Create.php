<?php

namespace App\Controller\Permission;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Permission;

final class Create
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $permission = new Permission();
        $permission->name = $body['name'];
        $permission->alias = strtolower($body['alias']);
        $permission->created_by = $session->user_id;

        // $permission->creatingCustom();

        $permission->save();

        $res = [
            'data' => [
                'session' => $session,
                'permission' => $permission,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}