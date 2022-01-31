<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class ProductReview
{
    private static $table = 'products_reviews';

    public static function up()
    {
        if (!Capsule::schema()->hasTable(self::$table)) {
            Capsule::schema()->create(self::$table, function ($table) {
                $table->bigIncrements('id');

                $table->unsignedBigInteger('product_id')->nullable();
                $table->foreign('product_id')->references('id')->on('products');

                $table->unsignedBigInteger('parent_id')->nullable();
                $table->foreign('parent_id')->references('id')->on(self::$table);

                $table->string('content');

                $table->smallInteger('rating')->nullable();

                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users');

                $table->timestamps();
            });
        }
    }

    public static function down()
    {
        if (Capsule::schema()->hasTable(self::$table)) {
            Capsule::schema()->dropIfExists(self::$table);
        }
    }
}