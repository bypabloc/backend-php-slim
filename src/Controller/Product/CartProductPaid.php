<?php

namespace App\Controller\Product;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\CartProduct;

final class CartProductPaid
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $cart_product = CartProduct::find($body['cart_product_id']);
        $cart_product->state = 2;
        $cart_product->save();

        $cart_product->productDiscountStock();

        $cart_product = CartProduct::find($body['cart_product_id']);
        $cart_product->product;

        $res = [
            'data' => [
                'cart_product' => $cart_product,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}