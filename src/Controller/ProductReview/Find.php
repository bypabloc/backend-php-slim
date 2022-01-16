<?php

namespace App\Controller\ProductReview;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\ProductReview;

final class Find
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $params = $request->getAttribute('params');

        $product_review = ProductReview::where('id',$params['id'])->with('children')->with('images')->get();
        foreach ($product_review as $key => $value) {
            $product_review[$key]['hasChildren'] = $value->children->count() > 0 ? true : false;
        }

        $res = [
            'data' => [
                'product_review' => $product_review,
            ],
        ];
        return $this->response($response, 200, $res);
}
}