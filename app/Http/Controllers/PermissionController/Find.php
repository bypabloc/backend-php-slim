<?php

namespace App\Http\Controllers\PermissionController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Permission;

class Find extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $permission = Permission::where('id',$query['id'])->get();

        return response()->json([
            'data' => [
                'permission' => $permission,
            ],
        ], 200);
    }
}
