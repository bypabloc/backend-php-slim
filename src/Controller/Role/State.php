<?php

namespace App\Controller\Role;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Role;

final class State
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $role = Role::find($body['id']);
        $role->is_active = $body['state'];
        // $role->updatingCustom();
        $role->save();

        $res = [
            'data' => [
                'role' => $role,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}