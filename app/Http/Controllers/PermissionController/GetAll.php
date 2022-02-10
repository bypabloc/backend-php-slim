<?php

namespace App\Http\Controllers\PermissionController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Permission;

class GetAll extends Controller
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

        $page = $query['page'];
        $per_page = $query['per_page'];

        $permissions = new Permission();
        if(isset($query['sort_by'])){
            $permissions = $permissions->orderBy($query['sort_by'], $query['sort_direction']);
        }

        $permissions = $permissions->paginate(
            $per_page, // per page (may be get it from request)
            ['*'], // columns to select from table (default *, means all fields)
            'page', // page name that holds the page number in the query string
            $page // current page, default 1
        );

        return response()->json([
            'data' => [
                'permissions' => $permissions,
            ],
        ], 200);
    }
}
