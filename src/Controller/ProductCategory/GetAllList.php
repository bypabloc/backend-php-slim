<?php

namespace App\Controller\ProductCategory;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Model\ProductCategory;

use App\Services\Pagination;

final class GetAllList
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $params = $request->getAttribute('params');

        $products_categories = new ProductCategory;
        
        $products_categories = $products_categories
            ->whereNull('parent_id')
            ->whereNull('user_id')
            ->with('children')
            ->pagination((int) $params['page'], (int) $params['per_page']);

        foreach ($products_categories['list'] as $key => $value) {
            $products_categories['list'][$key]['hasChildren'] = $value['children']->count();
            unset($products_categories['list'][$key]['children']);
        }
        
        $res = [
            'data' => [
                'products_categories' => $products_categories,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}