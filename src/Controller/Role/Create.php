<?php

namespace App\Controller\Role;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Role;
use App\Model\RolePermission;

final class Create
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $user_id = $session->user_id;

        $role = new Role();
        $role->name = $body['name'] ?? '';
        $role->created_by = $user_id;

        $role->save();

        // RolePermission::whereIn('permission_id', $body['permissions'])->delete();

        $role_id = $role->id;
        
        $roles_permissions = [];
        foreach ($body['permissions'] as $permission) {
            array_push($roles_permissions, [
                'role_id' => $role_id,
                'permission_id' => $permission,
                'created_by' => $user_id,
            ]);
        }
        RolePermission::insert($roles_permissions);

        $res = [
            'data' => [
                'session' => $session,
                'role' => $role,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}