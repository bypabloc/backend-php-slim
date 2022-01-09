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
        $params = $request->getAttribute('params');

        $check_permission_admin = $request->getAttribute('check_permission_admin');
        $products = new Product;
        if (!$check_permission_admin) {
            $session = $request->getAttribute('session');
            $user_id = $session->user_id;
            $products = $products->where('user_id', '!=',$user_id);
        }
        $products = $products->with('salesCanceled');
        $products = $products->with('salesRequest');
        $products = $products->with('salesPaid');
        $products = $products->with('salesSent');
        $products = $products->with('salesDelivered');
        $products = $products->with('salesFinalized');

        $products = $products->pagination((int) $params['page'], (int) $params['per_page']);
        
        $res = [
            'data' => [
                'products' => $products,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}