<?php

namespace App\Controller\Product;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Model\Product;

use App\Services\Pagination;

final class GetAll
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        try{
            $params = $request->getAttribute('params');

            $check_permission_admin = $request->getAttribute('check_permission_admin');
            $session = $request->getAttribute('session');
            $user_id = $session->user_id;

            $products = new Product();
            if (!$check_permission_admin) {
                $products = $products->where('user_id', $user_id);
            }

            $products = $products->with(['salesCanceled' => function ($query) use ($user_id) {
                $query->where('carts_products.user_id', '!=', $user_id);
            }]);
            $products = $products->with(['salesRequest' => function ($query) use ($user_id) {
                $query->where('carts_products.user_id', '!=', $user_id);
            }]);
            $products = $products->with(['salesPaid' => function ($query) use ($user_id) {
                $query->where('carts_products.user_id', '!=', $user_id);
            }]);
            $products = $products->with(['salesSent' => function ($query) use ($user_id) {
                $query->where('carts_products.user_id', '!=', $user_id);
            }]);
            $products = $products->with(['salesDelivered' => function ($query) use ($user_id) {
                $query->where('carts_products.user_id', '!=', $user_id);
            }]);
            $products = $products->with(['salesFinalized' => function ($query) use ($user_id) {
                $query->where('carts_products.user_id', '!=', $user_id);
            }]);
            
            $products = $products->with('rating');
            $products = $products->with('images')->pagination((int) $params['page'], (int) $params['per_page']);
            
            $res = [
                'data' => [
                    'products' => $products,
                ],
            ];
            return $this->response($response, 200, $res);
        } catch (\Throwable $th) {
            $response = new Response();
            $response = $this->response($response, 500, [
                'message' => $th->getMessage(),
                'getFile' => $th->getFile(),
                'getLine' => $th->getLine(),
            ]);
            return $response;
        }
    }
}