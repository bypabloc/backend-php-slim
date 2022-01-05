<?php

namespace App\Controller\Cart;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Cart;

final class Create
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $cart = new Cart();

        $cart->user_id = $body['user_id'];
        if(!empty($body['observation'])){
            $cart->observation = $body['observation'];
        }
        if(!empty($body['address'])){
            $cart->address = $body['address'];
        }
        if(!empty($body['state'])){
            $cart->state = $body['state'];
        }

        $cart->creatingCustom();

        $cart->save();

        if(!empty($body['products'])){
            $cart->addProducts($body['products']);
        }

        $cart = Cart::where('id',$cart->id)->with('products')->first();

        $res = [
            'data' => [
                'cart' => $cart,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}