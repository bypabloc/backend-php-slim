<?php

namespace App\Controller\Role;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Role;
use App\Model\RolePermission;

final class AssignPermissions
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $user_id = $session->user_id;
        $role_id = $body['id'];

        RolePermission::where('role_id', $role_id)->delete();
        
        $roles_permissions = [];
        foreach ($body['permissions'] as $permission) {
            array_push($roles_permissions, [
                'role_id' => $role_id,
                'permission_id' => $permission,
                'created_by' => $user_id,
            ]);
        }
        RolePermission::insert($roles_permissions);

        $role = Role::find($role_id);

        // Role::where('id', $role_id)->update([
        //     'updated_by' => $user_id,
        // ]);

        $res = [
            'data' => [
                'role' => $role,
            ],
            'message' => 'Permission assigned successfully',
        ];
        return $this->response($response, 200, $res);
    }
}