<?php

namespace App\Http\Controllers\RoleController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Role;

class FindOne extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $role = Role::find($query['id']);

        return response()->json([
            'message' => 'Role found successfully.',
            'data' => [
                'role' => $role,
            ]
        ], 200);
    }
}
