<?php

namespace App\Http\Middleware\Validations\Requests\PermissionValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Find
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['query'], [
            'id' => ['required', 'integer','exists:permissions,id']
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $request->merge([
            'query' => $validator->validated(),
        ]);

        return $next($request);
    }
}
