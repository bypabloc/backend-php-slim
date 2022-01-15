<?php

namespace App\Controller\ProductCategory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\ProductCategory;
use App\Model\Product;

final class GetBySlug
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $args = $request->getAttribute('args');

        $product_category = ProductCategory::where('id', $args['product_category']->id)->with('children')->where('is_active', 1)->get()->first();

        $products = Product::where('product_category_id', $product_category->id)->where('state', 1)->get();

        $res = [
            'data' => [
                'product_category' => $args['product_category'],
                'products' => $products,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}