<?php

namespace App\Http\Controllers\DiscountController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Discount;

class State extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];
        // $discount = new Discount;
        $discount = Discount::where('id', $body['id'])
        ->where('created_by', Auth::user()->id)
        ->get();

        !empty($discount) ? $discount = $discount->first() : $discount;

        if(isset($body['is_active'])){
            $discount->is_active = $body['is_active'];
        }

        $discount->save();

        // $discount = Discount::where('id', $body['id'])
        // ->with(['discount_config'])
        // ->get();

        return response()->json([
            'message' => 'Discount updatte successfully.',
            'data' => [
                'discount' => $discount,
            ]
        ], 201);
    }
}
