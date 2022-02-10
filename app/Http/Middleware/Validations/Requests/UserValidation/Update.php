<?php

namespace App\Http\Middleware\Validations\Requests\UserValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use App\Http\Middleware\IsBase64;

class Update
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
            'id' => ['required', 'integer', 'exists:users,id'],
            'email' => [
                'email','min:6', 'max:50',
                !empty($request->id) ? 'unique:users,email,'.$request->id :null
            ],
            'nickname' => [
                'alpha_num','min:6', 'max:10',
                !empty($request->id) ? 'unique:users,nickname,'.$request->id :null
            ],
            'sex'=>['integer', 'between:1,3'],
            'birthday'=>['date','before:today'],
            'password' => [Password::defaults()],
            'passwordConfirmation' => ['same:password', 'required_with:password'],
            'role_id' => ['integer', 'exists:roles,id'],
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
