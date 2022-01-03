<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class CartUser
{
    public static function up()
    {
        if (!Capsule::schema()->hasTable('carts_users')) {
            Capsule::schema()->create('carts_users', function ($table) {
                $table->bigIncrements('id');

                $table->unsignedBigInteger('cart_id');
                $table->foreign('cart_id')->references('id')->on('carts');

                $table->unsignedBigInteger('product_id');
                $table->foreign('product_id')->references('id')->on('products');

                $table->double('price', 8, 2);
                $table->double('qty', 8, 2);

                $table->timestamps();
            });
        }
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists('carts_users');
    }
}