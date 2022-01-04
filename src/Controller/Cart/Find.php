<?php

namespace App\Controller\ProductCategory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\ProductCategory;

final class Find
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $params = $request->getAttribute('params');

        // $product_category = ProductCategory::find($params['id'])->with('children');
        $product_category = ProductCategory::where('id',$params['id'])->with('children')->get();
        foreach ($product_category as $key => $value) {
            $product_category[$key]['hasChildren'] = $value->children->count() > 0 ? true : false;
        }

        $res = [
            'data' => [
                'product_category' => $product_category,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}