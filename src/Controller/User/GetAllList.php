<?php

namespace App\Controller\User;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Model\User;

use App\Services\Pagination;

final class GetAllList
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $params = $request->getAttribute('params');
        $session = $params['session'];
        
        $users = User::withCount(['products' => function ($query) {
                $query->where('state', 1);
            }])
            ->pagination((int) $params['page'], (int) $params['per_page']);
        
        $res = [
            'data' => [
                'users' => $users,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}