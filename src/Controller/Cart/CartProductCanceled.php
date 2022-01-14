<?php

namespace App\Controller\Cart;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\CartProduct;

final class CartProductCanceled
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $cart_product = CartProduct::find($body['cart_product_id']);
        $cart_product->state = 0;
        $cart_product->save();

        $cart_product->productRestoreStock();

        $cart_product->product;

        $res = [
            'data' => [
                'cart_product' => $cart_product,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}