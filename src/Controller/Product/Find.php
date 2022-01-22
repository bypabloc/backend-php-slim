<?php

namespace App\Controller\Product;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Product;

final class Find
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $params = $request->getAttribute('params');

        $check_permission_admin = $request->getAttribute('check_permission_admin');
        $session = $request->getAttribute('session');
        $user_id = $session->user_id;

        $product = new Product();
        $product = $product->where('id', $params['id'])->with('images');
        if (!$check_permission_admin) {
            $product = $product->where('user_id', $user_id);
        }

        $product = $product->with(['salesCanceled' => function ($query) use ($user_id) {
            $query->where('carts_products.user_id', '!=', $user_id);
        }]);
        $product = $product->with(['salesRequest' => function ($query) use ($user_id) {
            $query->where('carts_products.user_id', '!=', $user_id);
        }]);
        $product = $product->with(['salesPaid' => function ($query) use ($user_id) {
            $query->where('carts_products.user_id', '!=', $user_id);
        }]);
        $product = $product->with(['salesSent' => function ($query) use ($user_id) {
            $query->where('carts_products.user_id', '!=', $user_id);
        }]);
        $product = $product->with(['salesDelivered' => function ($query) use ($user_id) {
            $query->where('carts_products.user_id', '!=', $user_id);
        }]);
        $product = $product->with(['salesFinalized' => function ($query) use ($user_id) {
            $query->where('carts_products.user_id', '!=', $user_id);
        }]);

        $products = $product->with('productRating');
        $product = $product->with('images');
        $product = $product->first();
        

        $res = [
            'data' => [
                'product' => $product,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}