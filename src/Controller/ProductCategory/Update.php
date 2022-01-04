<?php

namespace App\Controller\ProductCategory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\ProductCategory;

final class Update
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $product_category = ProductCategory::find($body['id']);
        $product_category->name = $body['name'];
        if(!empty($body['parent_id'])){
            $product_category->parent_id = $body['parent_id'];
        }
        if(!empty($body['is_active'])){
            $product_category->is_active = $body['is_active'];
        }

        $product_category->updatingCustom();

        $product_category->save();

        $res = [
            'data' => [
                'product_category' => $product_category,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}