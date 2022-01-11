<?php

namespace App\Controller\Product;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Product;

final class Create
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $product = new Product();
        $product->name = $body['name'];
        if(isset($body['description'])){
            $product->description = $body['description'];
        }
        $product->price = $body['price'];
        if(isset($body['discount_type'])){
            $product->discount_type = $body['discount_type'];
        }
        if(isset($body['discount_quantity'])){
            $product->discount_quantity = $body['discount_quantity'];
        }
        $product->stock = $body['stock'];
        if(isset($body['image'])){
            $product->image = $body['image'];
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
        if(isset($body['state'])){
            $product->state = $body['state'];
        }
        $product->user_id = $body['user_id'];
        $product->product_category_id = $body['product_category_id'];

        $product->creatingCustom();

        $product->save();

        $res = [
            'data' => [
                'product' => $product,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}