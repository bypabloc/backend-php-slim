<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class Session
{
    private static $table = 'sessions';

    public static function up()
    {
        if (!Capsule::schema()->hasTable(self::$table)) {
            Capsule::schema()->create(self::$table, function ($table) {
                $table->string('token');
                $table->primary('token');

                $table->timestamp('expired_at', $precision = 0);
                
                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users');
    
                $table->timestamps();
            });
        }
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists(self::$table);
    }
}