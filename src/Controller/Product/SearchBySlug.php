<?php

namespace App\Controller\Product;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Product;

final class SearchBySlug
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $params = $request->getAttribute('params');

        // siguiendo la documentacion de postgresql del siguiente enlace:
        // https://www.postgresql.org/docs/9.0/functions-matching.html

        // $products = Product::where('slug', 'ILIKE', '%' . $params['p'] . '%')->get(); // con ILIKE

        // $products = Product::whereRaw("slug SIMILAR TO '%(" . $params['p'] . ")%'")->get(); // con SIMILAR TO

        $products = Product::whereRaw("slug ~* '" . str_replace(' ', '|', $params['p']) . "'")->get(); // con regex

        $res = [
            'data' => [
                'params' => $params,
                'products' => $products,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}