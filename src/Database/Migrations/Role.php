<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class Role
{
    public static function up()
    {
        if (!Capsule::schema()->hasTable('roles')) {
            Capsule::schema()->create('roles', function ($table) {
                $table->bigIncrements('id');
                $table->string('name')->unique();
                $table->boolean('is_active')->default(1);

                $table->unsignedBigInteger('created_by');
                $table->foreign('created_by')->references('id')->on('users');

                $table->timestamps();
            });
        }
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists('roles');
    }
}