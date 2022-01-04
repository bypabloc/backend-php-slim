<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class Role
{
    private static $table = 'roles';

    public static function up()
    {
        if (!Capsule::schema()->hasTable(self::$table)) {
            Capsule::schema()->create(self::$table, function ($table) {
                $table->bigIncrements('id');
                $table->string('name')->unique();
                $table->boolean('is_active')->default(1);

                $table->unsignedBigInteger('created_by');
                $table->foreign('created_by')->references('id')->on('users');

                $table->timestamps();
            });

            Capsule::table(self::$table)->insert(
                [
                    [
                        'name' => 'admin',
                        'created_by' => 1,
                    ],
                    [
                        'name' => 'user',
                        'created_by' => 1,
                    ],
                ]
            );
        }
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists(self::$table);
    }
}