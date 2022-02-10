<?php

namespace App\Http\Middleware\Validations\Requests\UserValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use App\Http\Middleware\IsBase64;

class Create
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
            $validator = Validator::make($request['body'], [
                'email' => ['required','email','min:6', 'max:50','unique:users'],
                'nickname' => ['required', 'alpha_num','min:6', 'max:10','unique:users'],
                'sex'=>['required','integer', 'between:1,3'],
                'birthday'=>['required','date','before:today'],
                'password' => ['required', Password::defaults()],
                'passwordConfirmation' => ['required','same:password'],
                'role_id' => ['required', 'integer', 'exists:roles,id'],
                'is_active' => ['boolean'],
                'image' => [new IsBase64(
                    types: ['png','jpg', 'jpeg', 'gif'],
                    size: 2048,
                )],

            ]);

            // if (!$check_permission_admin) {
            //     $body['user_id'] = $session->user_id;
            //     $validator['user_id'] = ['integer'];
            // }

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
