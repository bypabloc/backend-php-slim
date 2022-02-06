<?php

namespace App\Controller\Discount;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Discount;
use App\Model\DiscountConfig;

final class Create
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $discount = new Discount();

        $discount->coupon = $body['coupon'];
        $discount->discount_type = $body['discount_type'];
        $discount->discount_quantity = $body['discount_quantity'];
        $discount->mount_max_discount = $body['mount_max_discount'];
        
        if(isset($body['is_active'])){
            $discount->is_active = $body['is_active'];
        }

        $discount->created_by = $body['created_by'];
        $discount->expired_at = $body['expired_at'];

        $discount->save();

        if(isset($body['user_id'])||isset($body['product_id'])||isset($body['category_id'])){
                       
            $insert_array=[];
            $table_name ="";

            if(isset($body['user_id'])){
                $insert_array = $body['user_id'];
                $table_name ="users";
            }

            if(isset($body['product_id'])){
                $insert_array = $body['product_id'];
                $table_name ="products";
            }

            if(isset($body['category_id'])){
                $insert_array = $body['category_id'];
                $table_name ="products_categories";
            }

            $discount_config_model= new DiscountConfig();
            $discounts_config = [];
            $current_time = time();
            $current_time_format= date('Y-m-d h:i:s', $current_time);
            foreach ($insert_array as $discounts) {
                array_push($discounts_config,[
                    "table_id" => $discounts,
                    "discount_id" =>$discount->id,
                    "table_name" => $table_name,
                    "created_at"=>$current_time_format,
                    "updated_at"=>$current_time_format,
                ]);
                
            }
            
            DiscountConfig::insert($discounts_config);
        }
        
        $discount= Discount::where('id',$discount->id)
        ->with(['discount_config'])
        ->get();

        $res = [
            'data' => [
                'discount' => $discount,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}