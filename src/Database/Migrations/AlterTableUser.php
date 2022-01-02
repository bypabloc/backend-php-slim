<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class AlterTableUser
{
    public static function up()
    {
        if (Capsule::schema()->hasTable('users')) {
            Capsule::schema()->table('users', function ($table) {
                $table->unsignedBigInteger('role_id')->nullable();
                $table->foreign('role_id')->references('id')->on('roles');
            });

            Capsule::table('users')->where('id', 1)->update(['role_id' => 1]);
            Capsule::table('users')->where('id', 2)->update(['role_id' => 2]);

            Capsule::schema()->table('users', function ($table) {
                $table->unsignedBigInteger('role_id')->nullable(false)->change();
            });
        }
    }

    public static function down()
    {
        if (Capsule::schema()->hasTable('users')) {
            Capsule::schema()->table('users', function ($table) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            });
        }
    }
}