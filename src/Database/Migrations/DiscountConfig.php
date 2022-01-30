<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class DiscountConfig
{

    private static $table = 'discounts_configs';

    public static function up()
    {
        if (!Capsule::schema()->hasTable(self::$table)) {
            Capsule::schema()->create(self::$table, function ($table) {
                $table->bigIncrements('id');

                $table->integer('table_id');

                $table->string('table_name');
                
                $table->unsignedBigInteger('discount_id')->nullable();
                $table->foreign('discount_id')->references('id')->on('discounts');

                $table->timestamps();
            });
        }
    }

    public static function down()
    {
        if (Capsule::schema()->hasTable(self::$table)) {
            Capsule::schema()->dropIfExists(self::$table);
        }
    }
}
