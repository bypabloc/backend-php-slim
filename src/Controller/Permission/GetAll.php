<?php

namespace App\Controller\Permission;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Model\Permission;

use App\Services\Pagination;

final class GetAll
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        $params = $request->getAttribute('params');
        $session = $params['session'];
        
        $permissions = Permission::pagination((int) $params['page'], (int) $params['per_page']);
        
        $res = [
            'data' => [
                'permissions' => $permissions,
            ],
        ];
        return $this->response($response, 200, $res);
    }
}