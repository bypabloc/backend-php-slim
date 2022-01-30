<?php

namespace App\Controller\Migration;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Serializer\JsonResponse;

use App\Database\Migrations;

final class Up
{
    use JsonResponse;

    public function __invoke(Request $request, Response $response): Response
    {
        Migrations\User::up();
        Migrations\Session::up();

        Migrations\Role::up();
        Migrations\Permission::up();
        Migrations\RolePermission::up();

        Migrations\AlterTableUser::up();

        Migrations\ProductCategory::up();
        Migrations\Product::up();
        Migrations\Image::up();
        Migrations\Discount::up();
        Migrations\Cart::up();
        Migrations\CartProduct::up();
        Migrations\ProductReview::up();


        return $this->response($response, 200, []);
    }
}