<?php

namespace App\Http\Controllers\RoleController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Role;

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

        return response()->json([
            'role' => $role,
        ]);
    }
}
