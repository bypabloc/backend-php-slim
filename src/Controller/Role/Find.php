<?php

namespace App\Controller\Role;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Role;

final class Find
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $params = $request->getAttribute('params');

        $role = Role::find($params['id']);

        $res = [
            'data' => [
                'role' => $role,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}