<?php

namespace App\Controller\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Services\Hash;

use App\Model\User;

final class State
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $session = $request->getAttribute('session');
        $body = $request->getAttribute('body');

        $user = User::find($body['id']);
        $user->is_active = $body['state'];

        $user->save();

        $res = [
            'data' => [
                'user' => $user,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}