<?php

namespace App\Controller\Discount;

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
        $session = $request->getAttribute('session');
        $user_id = $session->user_id;

        $discounts = new Discount();
        if (!$check_permission_admin) {
            $discounts = $discounts->where('created_by', $user_id);
        }
       
        $discounts = $products->pagination((int) $params['page'], (int) $params['per_page']);
        
        $res = [
            'data' => [
                'discounts' => $discounts,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}