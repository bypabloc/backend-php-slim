<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class DataParser
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        // session([
        //     'ipAddress' => $request->ip(),
        //     'userAgent' => $request->userAgent(),
        // ]);

        session()->put('ipAddress', $request->ip());
        session()->put('userAgent', $request->userAgent());

        $request->merge([
            'body' => $request->post(),
            'query' => $request->query(),
            'parameters' => $request->route()->parameters(),
            'token' => $token,
        ]);

        return $next($request);
    }
}
