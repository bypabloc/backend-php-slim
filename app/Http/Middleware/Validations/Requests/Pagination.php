<?php

namespace App\Http\Middleware\Validations\Requests;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class Pagination
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['query'], [
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $query = $request['query'];

        $query['page'] = $query['page'] ?? 1;
        $query['per_page'] = $query['per_page'] ?? 10;

        $request->merge([
            'query' => $query,
        ]);
        
        return $next($request);
    }
}
