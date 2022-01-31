<?php

namespace App\Controller\Cart;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Model\Cart;

final class MeActive
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $user_id = $session->user_id;

        $cart = Cart::where('user_id',$user_id)->where('state',1)->with('products')->latest()->first();
        
        $res = [
            'data' => [
                'cart' => $cart,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}