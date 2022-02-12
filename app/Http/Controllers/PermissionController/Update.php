<?php

namespace App\Http\Controllers\PermissionController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Permission;

class Update extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];
        $permission = Permission::find($body['id']);

        if(!empty($body['name'])){
            $permission->name = $body['name'];
            $permission->alias = $body['alias'];
        }

        if(isset($body['is_active'])){
            $permission->is_active = $body['is_active'];
        }

        $permission->save();

        return response()->json([
            'permission' => $permission,
        ]);
    }
}
