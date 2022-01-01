<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class User
{
    public static function up()
    {
        if (!Capsule::schema()->hasTable('users')) {
            Capsule::schema()->create('users', function ($table) {
                $table->bigIncrements('id');
                $table->string('nickname')->unique();
                $table->string('email')->unique();

                // $table->boolean('is_super')->default(false);
                
                $table->uuid('uuid')->unique();
                $table->string('password');
                $table->timestamps();
            });
        }
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists('users');
    }
}