<?php

namespace App\Controller\Migration;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Database\Migrations;

final class Down
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        Migrations\ProductReview::down();
        Migrations\CartProduct::down();
        Migrations\Cart::down();

        Migrations\Product::down();
        Migrations\ProductCategory::down();
        Migrations\Image::down();
        Migrations\AlterTableUser::down();
        Migrations\RolePermission::down();
        Migrations\Permission::down();
        Migrations\Role::down();
        Migrations\Session::down();
        Migrations\User::down();

        return $this->response($response, 200, []);
    }
}