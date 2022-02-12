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

class Update
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
            'id' => [
                'required',
                'integer',
                'exists:discounts,id'
            ],
            'coupon' => [
                'alpha_num',
                'not_regex:/\s/i',
                'between:5,15' ,
                !empty($request->id) ? 'unique:discounts,coupon,'.$request->id : null
            ],
            'discount_type' => [
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
                'min:0',
                'numeric'
            ],
            'is_active' => [
                'boolean'
            ],
            'expired_at'=>[
                'date',
                'after:today'
            ]
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
