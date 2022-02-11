<?php

namespace App\Http\Middleware\Validations\Requests\ProductCategoryValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class Find
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
        $validator = Validator::make($request['query'], [
            'id' => ['required', 'integer','exists:products_categories,id']
        ]);

        if (!$check_permission_admin) {
            $body['user_id'] = Auth::user()->id;
            // print_r($validator['id']);
            // $validator['id'] = ['exists:products_categories,'.$body['user_id']];
        }

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
