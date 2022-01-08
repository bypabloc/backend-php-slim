<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class Permission
{
    private static $table = 'permissions';

    public static function up()
    {
        if (!Capsule::schema()->hasTable(self::$table)) {
            Capsule::schema()->create(self::$table, function ($table) {
                $table->bigIncrements('id');

                $table->string('name')->unique(); // Ex: Crear productos
                $table->string('alias')->unique(); // Ex: create_products

                $table->boolean('is_active')->default(1);

                $table->unsignedBigInteger('created_by');
                $table->foreign('created_by')->references('id')->on('users');

                $table->timestamps();
            });

            Capsule::table(self::$table)->insert(
                [
                    [
                        'name' => 'products',
                        'alias' => 'products',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'carts',
                        'alias' => 'carts',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'users',
                        'alias' => 'users',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'products_categories',
                        'alias' => 'products_categories',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'roles',
                        'alias' => 'roles',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'permissions',
                        'alias' => 'permissions',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'carts.get_all.admin',
                        'alias' => 'carts.get_all.admin',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'carts.find.admin',
                        'alias' => 'carts.find.admin',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'carts.create.admin',
                        'alias' => 'carts.create.admin',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'carts.update.admin',
                        'alias' => 'carts.update.admin',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'carts.state.admin',
                        'alias' => 'carts.state.admin',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'products_categories.get_all.admin',
                        'alias' => 'products_categories.get_all.admin',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'products_categories.find.admin',
                        'alias' => 'products_categories.find.admin',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'products_categories.create.admin',
                        'alias' => 'products_categories.create.admin',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'products_categories.update.admin',
                        'alias' => 'products_categories.update.admin',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'products_categories.state.admin',
                        'alias' => 'products_categories.state.admin',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'products.get_all.admin',
                        'alias' => 'products.get_all.admin',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'products.find.admin',
                        'alias' => 'products.find.admin',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'products.create.admin',
                        'alias' => 'products.create.admin',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'products.update.admin',
                        'alias' => 'products.update.admin',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'products.state.admin',
                        'alias' => 'products.state.admin',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'products.request.admin',
                        'alias' => 'products.request.admin',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'products.to_pay.admin',
                        'alias' => 'products.to_pay.admin',
                        'created_by' => 1,
                    ],
                ],
            );
        }
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists(self::$table);
    }
}