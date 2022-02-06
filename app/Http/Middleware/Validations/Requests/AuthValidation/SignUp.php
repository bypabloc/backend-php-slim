<?php

namespace App\Http\Middleware\Validations\Requests\AuthValidation;

use Closure;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;

class SignUp
{
    public function handle(Request $request, Closure $next)
    {
        $validator = Validator::make($request['body'], [
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'nickname' => ['required', 'string', 'max:50', 'unique:users'],
            'sex'=>['required','integer', 'between:1,3'],
            'birthday'=>['required','date','before:today'],
            'password' => ['required', 'string', 'min:6', 'max:50'],
            'passwordConfirmation' => ['required', 'string', 'min:6', 'max:50', 'same:password'],
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
