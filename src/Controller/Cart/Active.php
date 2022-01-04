<?php

namespace App\Controller\Cart;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Model\Cart;

final class Active
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $user_id = $session->user->id;

        $cart = new Cart;
        $cart = $carts->where('user_id',$user_id)->where('state',1)->get();
        
        $res = [
            'data' => [
                'cart' => $cart,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}