<?php

namespace App\Controller\Product;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\Product;

final class Create
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $product = new Product();
        $product->name = $body['name'];
        if(!empty($body['state'])){
            $product->is_active = $body['state'];
        }
        if(!empty($body['user_id'])){
            $product->user_id = $body['user_id'];
        }
        $product->created_by = $session->user_id;

        $product->creatingCustom();

        $product->save();

        $res = [
            'data' => [
                'product' => $product,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}