<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class Permission
{
    public static function up()
    {
        if (!Capsule::schema()->hasTable('permissions')) {
            Capsule::schema()->create('permissions', function ($table) {
                $table->bigIncrements('id');

                $table->string('name')->unique(); // Ex: Crear productos
                $table->string('alias')->unique(); // Ex: create_products

                $table->boolean('is_active')->default(1);

                $table->unsignedBigInteger('created_by');
                $table->foreign('created_by')->references('id')->on('users');

                $table->timestamps();
            });

            Capsule::table('permissions')->insert(
                [
                    [
                        'name' => 'products_categories',
                        'alias' => 'products_categories',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'products',
                        'alias' => 'products',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'cart',
                        'alias' => 'cart',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'users',
                        'alias' => 'users',
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
                ],
            );
        }
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists('permissions');
    }
}