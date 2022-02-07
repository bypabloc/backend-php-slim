<?php

namespace App\Http\Middleware\Validations\Requests\AuthValidation;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class SignIn
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'user' => ['required', 'string', 'min:3', 'max:20'],
            'password' => ['required', 'string', 'min:6', 'max:50'],
            'remember_me'=>['boolean'],
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
