<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DataParser
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        $ipAddress = $request->ip();

        $request->merge([
            'body' => $request->post(),
            'query' => $request->query(),
            'token' => $token,
            'ip_address' => $ipAddress,
        ]);

        return $next($request);
    }
}
