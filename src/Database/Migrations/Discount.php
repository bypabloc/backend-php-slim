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
                $table->string('coupon');
                $table->primary('coupon');

                $table->unsignedBigInteger('product_id')->nullable();
                $table->foreign('product_id')->references('id')->on('products');

                $table->unsignedBigInteger('category_id')->nullable();
                $table->foreign('category_id')->references('id')->on('products_categories');

                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users');                

                $table->smallInteger('discount_type')->default(0);
                // 1 = percentage
                // 2 = amount
                $table->double('discount_quantity', 8, 2);

                $table->double('mount_mx_dsc', 8, 2);
                                
                $table->smallInteger('state')->default(1);

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