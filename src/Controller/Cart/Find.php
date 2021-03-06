<?php

namespace App\Controller\Cart;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Cart;

final class Find
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $params = $request->getAttribute('params');

        $cart = Cart::find($params['id']);
        $cart->updateProductsPrices();

        $cart = Cart::where('id',$cart->id)->with('products')->first();
        
        $res = [
            'data' => [
                'cart' => $cart,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}