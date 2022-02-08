<?php

namespace App\Http\Controllers\ProductCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ProductCategory;

class Create extends Controller
{

    public function __invoke(Request $request)
    {
        $body = $request['body'];
        $product_category = new ProductCategory();

        $product_category->name = $body['name'];
        $product_category->slug = $body['slug'];

        if(!empty($body['is_active'])){
            $product_category->is_active = $body['is_active'];
        }
        if(!empty($body['parent_id'])){
            $product_category->parent_id = $body['parent_id'];
        }
        if(isset($body['user_id'])){
            $product_category->user_id = $body['user_id'];
        }

        // $product_category->creatingCustom();

        $product_category->save();

        return response()->json([
            'product_category' => $product_category,
        ]);
    }
}
