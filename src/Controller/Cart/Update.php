<?php

namespace App\Controller\Cart;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Cart;

final class Update
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $cart = Cart::find($body['id']);
        if(!empty($body['observation'])){
            $cart->observation = $body['observation'];
        }
        if(!empty($body['address'])){
            $cart->address = $body['address'];
        }
        if(!empty($body['state'])){
            $cart->state = $body['state'];
        }
        $cart->save();

        if(!empty($body['products_new'])){
            $cart->addProducts($body['products_new']);
        }
        if(!empty($body['products_upd'])){
            $cart->updateProducts($body['products_upd']);
        }

        $cart->updateTotal();

        $cart = Cart::where('id',$cart->id)->with('products')->first();

        $res = [
            'data' => [
                'cart' => $cart,
                'body' => $body,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}