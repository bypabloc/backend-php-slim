<?php

namespace App\Http\Controllers\ProductCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ProductCategory;

class Update extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];

        $role = Role::find($body['id']);

        if(isset($body['name'])) {
            $role->name = $body['name'];
        }
        if(isset($body['is_active'])) {
            $role->is_active = $body['is_active'];
        }

        $role->save();

        return response()->json([
            'message' => 'Product Category updated successfully.',
            'data' => [
                'role' => $role,
            ]
        ], 200);
    }
}
