<?php

namespace App\Http\Controllers\DiscountController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Discount;

use App\Services\Response;

class Update extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];

        $discount = Discount::where('id', $body['id'])
        ->where('created_by', Auth::user()->id)
        ->get()
        ->first();

        if(!empty($discount)){

            if(isset($body['coupon'])){
                $discount->coupon = $body['coupon'];
            }
            if(isset($body['discount_type'])){
                $discount->discount_type = $body['discount_type'];
                $discount->discount_quantity = $body['discount_quantity'];
            }
            if(isset($body['mount_max_discount'])){
                $discount->mount_max_discount = $body['mount_max_discount'];
            }
            if(isset($body['is_active'])){
                $discount->is_active = $body['is_active'];
            }
            if(isset($body['expired_at'])){
                $discount->expired_at = $body['expired_at'];
            }
            $discount->save();

            $discount= Discount::where('id',$discount->id)
            ->with(['discount_config'])
            ->get();

            return Response::OK(
                message: 'Discount updatte successfully.',
                data: [
                    'discount' => $discount,
                ]
            );
        }else{
            return Response::UNAUTHORIZED(
                message: 'User is not owner to this discount',
                errors : 'User is not owner to this discount',
            );
        }
    }
}
