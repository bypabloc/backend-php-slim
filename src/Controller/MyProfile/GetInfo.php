<?php

namespace App\Controller\MyProfile;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Model\User;

final class GetInfo
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');

        $res = [
            'data' => [
                'user' => $session->user,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}