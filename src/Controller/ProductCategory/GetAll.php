<?php

namespace App\Controller\ProductCategory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Model\ProductCategory;

use App\Services\Pagination;

final class GetAll
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $params = $request->getAttribute('params');

        $check_permission_admin = $request->getAttribute('check_permission_admin');
        $products_categories = new ProductCategory;
        if (!$check_permission_admin) {
            $session = $request->getAttribute('session');
            $user_id = $session->user_id;
            $products_categories = $products_categories->where('user_id', $user_id);
        }
        $products_categories = $products_categories->pagination((int) $params['page'], (int) $params['per_page']);
        
        $res = [
            'data' => [
                'products_categories' => $products_categories,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}