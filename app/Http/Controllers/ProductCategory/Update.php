<?php

namespace App\Http\Controllers\ProductCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ProductCategory;

class Update extends Controller
{
    public function __invoke(Request $request)
    {
        $body = $request['body'];

        $product_category = ProductCategory::find($body['id']);

        if(isset($body['name'])) {
            $product_category->name = $body['name'];
            $product_category->slug = $body['slug'];
        }
        if(!empty($body['parent_id'])){
            $product_category->parent_id = $body['parent_id'];
        }
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
