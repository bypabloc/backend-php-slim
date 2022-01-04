<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class AlterTableUser
{
    private static $table = 'users';

    public static function up()
    {
        if (Capsule::schema()->hasTable(self::$table)) {
            Capsule::schema()->table(self::$table, function ($table) {
                $table->unsignedBigInteger('role_id')->nullable();
                $table->foreign('role_id')->references('id')->on('roles');
            });

            Capsule::table(self::$table)->where('id', 1)->update(['role_id' => 1]);
            Capsule::table(self::$table)->where('id', 2)->update(['role_id' => 2]);
            Capsule::table(self::$table)->where('id', 3)->update(['role_id' => 2]);

            Capsule::schema()->table(self::$table, function ($table) {
                $table->unsignedBigInteger('role_id')->nullable(false)->change();
            });
        }
    }

    public static function down()
    {
        if (Capsule::schema()->hasTable(self::$table)) {
            Capsule::schema()->table(self::$table, function ($table) {
                $table->dropForeign(['role_id']);
                $table->dropColumn('role_id');
            });
        }
    }
}