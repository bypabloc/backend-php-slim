<?php

namespace App\Controller\Discount;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Discount;

final class GetByCoupon
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $args = $request->getAttribute('args');
        
        $discount = $args['discount'];
        // coupon

        $res = [
            'data' => [
                'discount' => $discount,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}