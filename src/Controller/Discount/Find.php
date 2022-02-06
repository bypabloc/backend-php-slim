<?php

namespace App\Controller\Discount;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Discount;

final class Find
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $params = $request->getAttribute('params');

        $check_permission_admin = $request->getAttribute('check_permission_admin');
        $session = $request->getAttribute('session');
        $user_id = $session->user_id;

        $discounts = new Discount();
        $discounts = $discounts->where('id', $params['id'])->with(['discount_config']);
        if (!$check_permission_admin) {
            $discounts = $discounts->where('created_by', $user_id);
        }

        $discounts = $discounts->first();
        

        $res = [
            'data' => [
                'discounts' => $discounts,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}