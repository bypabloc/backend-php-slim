<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Permission;

class Find extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
