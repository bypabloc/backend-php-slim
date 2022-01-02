<?php

namespace App\Controller\Role;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Role;
use App\Model\RolePermission;

final class AssignPermission
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $user_id = $session->user_id;
        $role_id = $body['id'];
        $permission = $body['permission'];

        RolePermission::where('role_id', $role_id)->where('permission_id', $permission)->delete();
        
        RolePermission::insert([
            'role_id' => $role_id,
            'permission_id' => $permission,
            'created_by' => $user_id,
        ]);

        $role = Role::find($role_id);

        $res = [
            'data' => [
                'role' => $role,
            ],
            'message' => 'Permission assigned successfully',
        ];
        return $this->response($response, 200, $res);
    }
}