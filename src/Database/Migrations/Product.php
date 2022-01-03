<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class Product
{
    public static function up()
    {
        if (!Capsule::schema()->hasTable('products')) {
            Capsule::schema()->create('products', function ($table) {
                $table->bigIncrements('id');

                $table->string('name',255);
                $table->string('description',250)->nullable();
                $table->string('slug',255);

                $table->double('price', 8, 2);

                $table->smallInteger('discount_type')->default(0);
                // 1 = percentage
                // 2 = amount
                $table->double('discount_quantity', 8, 2)->nullable();

                $table->integer('stock');

                $table->string('image',255)->nullable();

                $table->string('weight',50)->nullable();
                $table->string('height',50)->nullable();
                $table->string('width',50)->nullable();
                $table->string('length',50)->nullable();

                $table->integer('likes')->default(0);

                $table->smallInteger('state')->default(1);

                $table->unsignedBigInteger('product_category_id');
                $table->foreign('product_category_id')->references('id')->on('products_categories');

                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users');
                
                $table->timestamps();
            });
        }
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists('products');
    }
}