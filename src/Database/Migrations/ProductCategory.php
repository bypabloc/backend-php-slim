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

                $table->string('name',20)->unique();
                $table->string('slug',20)->unique();

                $table->boolean('is_active')->default(1);

                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users');

                $table->unsignedBigInteger('created_by');
                $table->foreign('created_by')->references('id')->on('users');
                
                $table->timestamps();
            });

            // products_categories_users
        }
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists('products_categories');
    }
}