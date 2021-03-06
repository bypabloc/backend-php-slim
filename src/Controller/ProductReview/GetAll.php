<?php

namespace App\Controller\ProductReview;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Model\ProductReview;

use App\Services\Pagination;

final class GetAll
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        try{
            $params = $request->getAttribute('params');

            $products_reviews = new ProductReview;
            
            $session = $request->getAttribute('session');
            $user_id = $session->user_id;
            $products_reviews = $products_reviews->where('user_id', $user_id);
            
            $products_reviews = $products_reviews->with('images')->pagination((int) $params['page'], (int) $params['per_page']);
            
            $res = [
                'data' => [
                    'products_reviews' => $products_reviews,
                ],
            ];
            return $this->response($response, 200, $res);
            
        } catch (\Throwable $th) {
            $response = new Response();
            $response = $this->response($response, 500, [
                'message' => $th->getMessage(),
                'getFile' => $th->getFile(),
                'getLine' => $th->getLine(),
            ]);
            return $response;
        }
    }
}