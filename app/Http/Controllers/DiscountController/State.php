<?php

namespace App\Http\Controllers\DiscountController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Discount;

use App\Services\Response;

class State extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];

        $discount = Discount::where('id', $body['id'])
        ->where('created_by', Auth::user()->id)
        ->get()
        ->first();

        if(empty($discount)){
            return Response::UNAUTHORIZED(
                message: 'User is not owner to this discount',
                errors : 'User is not owner to this discount',
            );
        }else{
            if(isset($body['is_active'])){
                $discount->is_active = $body['is_active'];
            }
            $discount->save();

            return Response::OK(
                message: 'Discount updatte successfully.',
                data: [
                    'discount' => $discount,
                ]
            );
        }
    }
}
