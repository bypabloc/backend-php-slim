<?php

namespace App\Controller\Cart;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as RequestClass;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Cart;

final class Request
{
    use JsonResponse;

    public function __invoke(RequestClass $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $cart = Cart::find($body['id']);
        $cart->state = 2;
        $cart->save();

        $cart = Cart::where('id',$cart->id)->with('products')->first();

        $res = [
            'data' => [
                'cart' => $cart,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}