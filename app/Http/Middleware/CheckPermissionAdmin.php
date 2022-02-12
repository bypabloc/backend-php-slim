<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermissionAdmin
{
    public function handle(Request $request, Closure $next, $permission)
    {
        $canPermission = Auth::user()->role->can($permission);
        $request->merge([
            'check_permission_admin' => $canPermission
        ]);
        return $next($request);
    }
}
