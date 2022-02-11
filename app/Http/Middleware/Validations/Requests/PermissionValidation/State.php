<?php

namespace App\Http\Middleware\Validations\Requests\PermissionValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class State
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'id' => ['required', 'integer','exists:permissions,id'],
            'is_active' => ['boolean'],
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $request->merge([
            'body' => $validator->validated(),
        ]);

        return $next($request);
    }
}
