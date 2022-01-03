<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class Cart
{
    public static function up()
    {
        if (!Capsule::schema()->hasTable('carts')) {
            Capsule::schema()->create('carts', function ($table) {
                $table->bigIncrements('id');

                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users');

                $table->double('price', 8, 2);

                $table->string('observation')->nullable();

                $table->string('address')->nullable();

                $table->smallInteger('state')->default(1);
                
                $table->timestamps();
            });
        }
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists('carts');
    }
}