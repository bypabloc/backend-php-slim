<?php

namespace App\Http\Controllers\RoleController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Role;

class Update extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];

        $role = Role::find($body['id']);

        $role->name = $body['name'];
        
        $role->save();

        return response()->json([
            'message' => 'Role updated successfully.',
            'data' => [
                'role' => $role,
            ]
        ], 200);
    }
}
