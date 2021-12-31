<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class User
{
    public static function up()
    {
        Capsule::schema()->create('users', function ($table) {
            $table->increments('id');
            $table->string('nickname')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists('users');
    }
}