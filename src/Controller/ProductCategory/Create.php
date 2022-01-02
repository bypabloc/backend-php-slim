<?php

namespace App\Controller\ProductCategory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\ProductCategory;

final class Create
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $product_category = new ProductCategory();
        $product_category->name = $body['name'];
        $product_category->alias = strtolower($body['alias']);
        $product_category->created_by = $session->user_id;

        // $product_category->creatingCustom();

        $product_category->save();

        $res = [
            'data' => [
                'session' => $session,
                'product_category' => $product_category,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}