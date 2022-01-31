<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class Discount
{
    private static $table = 'discounts';
    public static function up()
    {
        if (!Capsule::schema()->hasTable(self::$table)) {
            Capsule::schema()->create(self::$table, function ($table) {
                $table->bigIncrements('id');

                $table->string('coupon');
                
                $table->smallInteger('discount_type')->default(1);
                // 1 = percentage
                // 2 = amount
                $table->double('discount_quantity', 8, 2);

                $table->double('mount_max_discount', 8, 2)->nullable();
                                
                $table->boolean('is_active')->default(1);

                $table->unsignedBigInteger('created_by');
                $table->foreign('created_by')->references('id')->on('users');
                
                $table->timestamp('expired_at', $precision = 0);

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