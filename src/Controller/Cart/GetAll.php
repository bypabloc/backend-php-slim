<?php

namespace App\Controller\Cart;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Model\Cart;

use App\Services\Pagination;

final class GetAll
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $params = $request->getAttribute('params');

        $check_permission_admin = $request->getAttribute('check_permission_admin');
        $carts = new Cart;
        if (!$check_permission_admin) {
            $session = $request->getAttribute('session');
            $user_id = $session->user_id;
            $carts = $carts->where('user_id', $user_id);
        }
        $carts = $carts->with('products')->pagination((int) $params['page'], (int) $params['per_page']);
        
        $res = [
            'data' => [
                'carts' => $carts,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}