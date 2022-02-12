<?php

namespace App\Http\Controllers\DiscountController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Discount;

class GetAll extends Controller
{
    public function __invoke(Request $request)
    {
        $query = $request['query'];

        $page = $query['page'];
        $per_page = $query['per_page'];

        $discount = new Discount();
        $discount = $discount->with(['discount_config'])->where('created_by', Auth::user()->id);
        if(isset($query['sort_by'])){
            $discount = $discount->orderBy($query['sort_by'], $query['sort_direction']);
        }

        $discount = $discount->paginate(
            $per_page, // per page (may be get it from request)
            ['*'], // columns to select from table (default *, means all fields)
            'page', // page name that holds the page number in the query string
            $page // current page, default 1
        );

        return response()->json([
            'data' => [
                'discount' => $discount,
            ],
        ], 200);
    }
}
