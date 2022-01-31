<?php

namespace App\Controller\Discount;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Product;
use App\Model\Image;

final class Update
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');
        $id_product = $body['id'];

        $product = Product::find($id_product);
        if(isset($body['name'])){
            $product->name = $body['name'];
        }
        if(isset($body['description'])){
            $product->description = $body['description'];
        }
        if(isset($body['price'])){
            $product->price = $body['price'];
        }
        if(isset($body['discount_type'])){
            $product->discount_type = $body['discount_type'];
        }
        if(isset($body['discount_quantity'])){
            $product->discount_quantity = $body['discount_quantity'];
        }
        if(isset($body['stock'])){
            $product->stock = $body['stock'];
        }
        if(isset($body['weight'])){
            $product->weight = $body['weight'];
        }
        if(isset($body['height'])){
            $product->height = $body['height'];
        }
        if(isset($body['width'])){
            $product->width = $body['width'];
        }
        if(isset($body['length'])){
            $product->length = $body['length'];
        }
        if(isset($body['likes'])){
            $product->likes = $body['likes'];
        }
        if(isset($body['state'])){
            $product->state = $body['state'];
        }
        if(isset($body['product_category_id'])){
            $product->product_category_id = $body['product_category_id'];
        }
        
        $product->updatingCustom();

        $product->save();

        if(isset($body['image'])){
            $image_model= new Image();

            $query_images = Image::where('table_id', $id_product)->where('table_name','products');
            $get_images = $query_images->get();

            foreach($get_images as $image){
                $image_model->deleteFile($image->path);            
            }    
            
            $query_images->delete();
            $images = [];

            $current_time = time();
            $current_time_format= date('Y-m-d h:i:s', $current_time);

            foreach ($body['image'] as $image) {
                $image_model->creatingImageProducts($image);
                array_push($images,[
                    "path" => $image_model->path,
                    "table_id" =>$id_product,
                    "table_name" => 'products',
                    "created_at"=>$current_time_format,
                    "updated_at"=>$current_time_format,
                ]);
                
            }
            
            Image::insert($images);
        }

        $product = Product::where('id',$product->id)->with('images')->get();
        
        $res = [
            'data' => [
                'product' => $product,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}