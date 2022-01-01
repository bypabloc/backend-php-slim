<?php

namespace App\Controller\Permission;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Permission;

final class Find
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $params = $request->getAttribute('params');

        $permission = Permission::find($params['id']);

        $res = [
            'data' => [
                'permission' => $permission,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}