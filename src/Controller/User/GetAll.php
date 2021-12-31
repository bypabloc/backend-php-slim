<?php

namespace App\Controller\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Model\User;

use App\Services\Pagination;

final class GetAll
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        try {
            $body = (array) $request->getParsedBody()['data'] ?? [];
            
            $users = User::pagination((int) $body['page'], (int) $body['per_page']);
            
            $data = [
                'users' => $users,
            ];
        } catch (\Throwable $th) {

            return $this->response($response, 500, [
                'errors' => [
                    'message' => $th->getMessage(),
                    'getFile' => $th->getFile(),
                    'getLine' => $th->getLine(),
                ],
            ]);
        }

        return $this->response($response, 200, $data);
    }
}