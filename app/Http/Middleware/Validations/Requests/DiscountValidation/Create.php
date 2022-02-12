<?php

namespace App\Http\Middleware\Validations\Requests\DiscountValidation;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\Rule;

use App\Http\Middleware\ListContent;
use App\Http\Middleware\ExistList;
use App\Http\Middleware\ListNotRepeat;

class Create
{
    public function handle(Request $request, Closure $next)
    {
        $check_permission_admin = $request['check_permission_admin'];

        if(isset($request['coupon'])){
            $request['coupon'] = strtoupper($request['coupon']);
        }
        $upperCaseToArray = array(
            'coupon' => $request['coupon']
        );
        $validate = array_merge($request['body'], $upperCaseToArray);

        $validator = Validator::make($validate, [
            'coupon' => [
                'required',
                'alpha_num',
                'not_regex:/\s/i',
                'between:5,15' ,
                'unique:discounts'
            ],
            'discount_type' => [
                'required',
                'integer',
                'between:0,2'
            ],
            'discount_quantity' => [
                'numeric',
                'min:0',
                'required_with:discount_type',
                Rule::when(($request->discount_type === 1), [
                    'between:0,100'
                ])
            ],
            'mount_max_discount' => [
                'required',
                'min:0',
                'numeric'
            ],  // ,'between:0,100' validation
            'is_active' => [
                'boolean'
            ],
            'expired_at'=>[
                'required',
                'date',
                'after:today'
            ],

            'user_id' => [
                'required_without_all:product_id,category_id',
                'array',
                Rule::when(is_array($request->user_id), [
                    new ListContent('integer'),
                    new ExistList('users', 'id'),
                    new ListNotRepeat()
                ])
            ],
            'product_id' => [
                'required_without_all:user_id,category_id',
                'array',
                Rule::when(is_array($request->product_id), [
                    new ListContent('integer'),
                    new ExistList('products', 'id'),
                    new ListNotRepeat()
                ])
            ],
            'category_id' => [
                'required_without_all:user_id,product_id',
                'array',
                Rule::when(is_array($request->category_id), [
                    new ListContent('integer'),
                    new ExistList('products_categories', 'id'),
                    new ListNotRepeat()
                ])
            ],
        ]);

        if (!$check_permission_admin) {
            $body['created_by'] = Auth::user()->id;
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
