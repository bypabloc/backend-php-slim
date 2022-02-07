<?php

namespace App\Http\Middleware\Validations\Requests\ProductCategoryValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str as Str;
use Illuminate\Validation\Rule;

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
        $session = $request['session'];
        $slug = Str::slug($request['body']['name']);
        $slugToArray = array('slug' => $slug);
        $validate = array_merge($request['body'], $slugToArray);

        $validator = Validator::make($validate, [
            'id' => ['required', 'integer','exists:products_categories,id'],
            'name' => [
                'regex:/^\w+( +\w+)*$/i' ,
                'not_regex:/\s{2,}/i',
                'min:5','max:20',
                'unique:products_categories,name,'.$request['body']['id']
            ],
            'is_active' => ['boolean'],
            'parent_id' => ['integer', 'exists:products_categories,id'],
            'slug' => ['unique:products_categories,slug,'.$request['body']['id']]
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
