<?php

namespace App\Http\Controllers\ProductCategory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\ProductCategory;

class GetAll extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $page = $query['page'];
        $per_page = $query['per_page'];
        // $check_permission_admin = $query['check_permission_admin'];
        $products_categories = new ProductCategory;

        // if (!$check_permission_admin) {
        //     $session = $query['session'];
        //     $user_id = $session->user_id;
            $products_categories = $products_categories->where('user_id', '1');
        // }
        $products_categories = $products_categories->whereNull('parent_id')->with('children');

        if(isset($query['sort_by'])){
            $products_categories = $products_categories->orderBy($query['sort_by'], $query['sort_direction']);
        }

        $products_categories = $products_categories->paginate(
            $per_page, // per page (may be get it from request)
            ['*'], // columns to select from table (default *, means all fields)
            'page', // page name that holds the page number in the query string
            $page // current page, default 1
        )->toArray();


        foreach ($products_categories['data'] as $key => $value) {
            $products_categories['data'][$key]['hasChildren'] = count($value['children']);
            unset($products_categories['data'][$key]['children']);
        }

        return response()->json([
            'data' => ['products_categories' => $products_categories],
        ]);

    }
}
