<?php

namespace App\Http\Controllers\ProductCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ProductCategory;

class State extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {

        $body = $request['body'];

        $product_category = ProductCategory::find($body['id']);

        if(isset($body['is_active'])) {
            $product_category->is_active = $body['is_active'];
        }

        $product_category->save();

        return response()->json([
            'message' => 'Product Category updated successfully.',
            'data' => [
                'product_category' => $product_category,
            ]
        ], 200);

    }
}
