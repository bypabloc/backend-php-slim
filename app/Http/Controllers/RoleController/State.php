<?php

namespace App\Http\Controllers\RoleController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Role;

class State extends Controller
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

        $role = Role::find($body['id']);
        if(isset($body['is_active'])) {
            $role->is_active = $body['is_active'];
        }

        $role->save();

        return response()->json([
            'message' => 'Role updated successfully.',
            'data' => [
                'role' => $role,
            ]
        ], 200);
    }
}
