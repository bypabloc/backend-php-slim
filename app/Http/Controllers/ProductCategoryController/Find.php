<?php

namespace App\Http\Controllers\ProductCategoryController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ProductCategory;

class Find extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $product_category = ProductCategory::where('id',$query['id'])->with('children')->get();

        foreach ($product_category as $key => $value) {
            $product_category[$key]['hasChildren'] = count($value['children']);
            unset($product_category[$key]['children']);
        }

        return response()->json([
            'message' => 'Product Category found successfully.',
            'data' => [
                'product_category' => $product_category,
            ]
        ], 200);

    }
}