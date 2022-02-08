<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CanPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

    public $permission;

    public function __construct($permission)
    {
        $this->permission = $permission;
    }

    public function handle(Request $request, Closure $next)
    {

        $permission = $this->permission;

        $session = Auth::user();

        $canPermission = $session->user()->role->can($this->permission);

        if(!$canPermission){
            return response()->json([
                'message' => 'Permission denied',
                'errors' => 'Permission denied',
            ], 401);
        }

        return $next($request);

    }
}
