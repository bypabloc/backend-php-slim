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
        if(!empty($body['name'])){
            $product->name = $body['name'];
        }
        if(!empty($body['description'])){
            $product->description = $body['description'];
        }
        if(!empty($body['price'])){
            $product->price = $body['price'];
        }
        if(!empty($body['discount_type'])){
            $product->discount_type = $body['discount_type'];
        }
        if(!empty($body['discount_quantity'])){
            $product->discount_quantity = $body['discount_quantity'];
        }
        if(!empty($body['stock'])){
            $product->stock = $body['stock'];
        }
        if(!empty($body['image'])){
            $product->image = $body['image'];
        }
        if(!empty($body['weight'])){
            $product->weight = $body['weight'];
        }
        if(!empty($body['height'])){
            $product->height = $body['height'];
        }
        if(!empty($body['width'])){
            $product->width = $body['width'];
        }
        if(!empty($body['length'])){
            $product->length = $body['length'];
        }
        if(!empty($body['likes'])){
            $product->likes = $body['likes'];
        }
        if(!empty($body['state'])){
            $product->state = $body['state'];
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