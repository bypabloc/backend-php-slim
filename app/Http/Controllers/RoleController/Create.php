<?php

namespace App\Http\Controllers\RoleController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Role;
use App\Models\RolePermission;

class Create extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $body = $request['body'];

        $role = new Role;

        $role->name = $body['name'];

        $role->save();
        $role_id = $role->id;
        $roles_permissions = [];
        foreach ($body['permissions'] as $permission) {
            array_push($roles_permissions, [
                'role_id' => $role_id,
                'permission_id' => $permission
            ]);
        }
        RolePermission::insert($roles_permissions);

        return response()->json([
            'message' => 'Role created successfully.',
            'data' => [
                'role' => $role,
            ]
        ], 201);
    }
}
