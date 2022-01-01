<?php

namespace App\Controller\Role;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Role;

final class Create
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $role = new Role();
        $role->name = $body['name'] ?? '';
        $role->created_by = $session->user_id;

        // $role->creatingCustom();

        $role->save();

        $res = [
            'data' => [
                'session' => $session,
                'role' => $role,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}