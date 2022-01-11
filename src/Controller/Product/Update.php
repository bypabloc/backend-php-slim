<?php

namespace App\Controller\Product;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Product;

final class Update
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $product = Product::find($body['id']);
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
        if(isset($body['image'])){
            if($product->image){
                $product->deleteFile($product->image);
            }
            $product->image = $body['image'];
        }else{
            unset($product->image);
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

        $res = [
            'data' => [
                'product' => $product,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}