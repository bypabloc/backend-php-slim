<?php

namespace App\Http\Middleware\Validations\Requests\ProductCategoryValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str as Str;
use Illuminate\Support\Facades\Auth;

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
        $check_permission_admin = $request['check_permission_admin'];
        if(!$request['body']){
            return response()->json([
                'message' => 'Validation failed',
                'errors' => 'Parameters is empty',
            ], 422);
        }else{
            $validate = $request['body'];
            if(isset($request['body']['name'])){
                $slug = Str::slug($request['body']['name']);
                $slugToArray = array('slug' => $slug);
                $validate = array_merge($request['body'], $slugToArray);
            }

            $validator = Validator::make($validate, [
                'name' => ['required','regex:/^\w+( +\w+)*$/i' ,'not_regex:/\s{2,}/i','min:5','max:20','unique:products_categories'],
                'is_active' => ['boolean'],
                'parent_id' => ['integer', 'exists:products_categories,id'],
                'user_id' => ['integer', 'exists:users,id'],
                'slug' => ['required', 'unique:products_categories'],
            ]);

            if (!$check_permission_admin) {
                $body['user_id'] = Auth::user()->id;
            }

            if($validator->fails()){
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }
        }
        $request->merge([
            'body' => $validator->validated(),
        ]);

        return $next($request);
    }
}
