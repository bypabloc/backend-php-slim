<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class ProductCategory
{
    public static function up()
    {
        if (!Capsule::schema()->hasTable('products_categories')) {
            Capsule::schema()->create('products_categories', function ($table) {
                $table->bigIncrements('id');

                $table->string('name');

                $table->string('slug');

                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users');

                $table->boolean('state')->default(1);
                
                $table->timestamps();
            });
        }
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists('products_categories');
    }
}