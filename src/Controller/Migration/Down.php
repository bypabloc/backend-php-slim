<?php

namespace App\Controller\Migration;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Database\Migrations\User;
use App\Database\Migrations\Session;
use App\Database\Migrations\Role;
use App\Database\Migrations\Permission;
use App\Database\Migrations\RolePermission;

final class Down
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        RolePermission::down();
        Permission::down();
        Role::down();
        Session::down();
        User::down();

        return $this->response($response, 200, []);
    }
}