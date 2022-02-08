<?php

namespace App\Http\Middleware\Validations\Requests\PermissionValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        if(!$request['body']){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => 'Parameters is empty',
            ], 422);
        }else{

            if(isset($request['name'])){
                $request['name'] = strtolower($request['name']);
            }
            if(isset($request['alias'])){
                $request['alias'] = strtolower($request['alias']);
            }
            $lowerCaseToArray = array(
                'alias' => $request['alias'],
                'name' => $request['name'],
            );
            $validate = array_merge($request['body'], $lowerCaseToArray);

            $validator = Validator::make($validate, [
                'id' => ['required', 'integer','exists:permissions,id'],
                'name' => ['string','min:5','max:20','unique:permissions,name,'.$request['body']['id']],
                'alias' => ['required_with:name','string','min:5','max:20','unique:permissions,alias,'.$request['body']['id']],
                'is_active' => ['boolean']
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
