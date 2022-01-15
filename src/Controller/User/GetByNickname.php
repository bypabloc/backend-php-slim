<?php

namespace App\Controller\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\User;

final class GetByNickname
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $args = $request->getAttribute('args');
        
        $user = $args['user'];

        $args['user']->products;
    
        $res = [
            'data' => [
                'user' => $user,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}