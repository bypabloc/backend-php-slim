<?php

namespace App\Controller\Product;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Product;

final class GetBySlug
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $args = $request->getAttribute('args');

        $product = $args['product']->related;

        $res = [
            'data' => [
                'product' => $args['product'],
            ],
        ];
        return $this->response($response, 200, $res);
    }
}