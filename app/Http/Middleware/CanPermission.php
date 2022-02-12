<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanPermission
{

    public function handle(Request $request, Closure $next, $permission)
    {
        $canPermission = Auth::user()->role->can($permission);

        if(!$canPermission){
            return response()->json([
                'message' => 'Permission denied',
                'errors' => 'Permission denied',
            ], 401);
        }

        return $next($request);

    }
}
