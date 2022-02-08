<?php

namespace App\Http\Middleware\Validations\Requests\PermissionValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class State
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
        if(!$request['body']){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => 'Parameters is empty',
            ], 422);
        }else{

            $validator = Validator::make($request['body'], [
                'id' => ['required', 'integer','exists:permissions,id'],
                'is_active' => ['boolean'],
            ]);

        // if (!$check_permission_admin) {
        //     $body['user_id'] = $session->user_id;
        //     $validator['user_id'] = ['integer'];
        // }
        }
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
