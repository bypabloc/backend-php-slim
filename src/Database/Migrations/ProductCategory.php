<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class ProductCategory
{
    private static $table = 'products_categories';

    public static function up()
    {
        if (!Capsule::schema()->hasTable(self::$table)) {
            Capsule::schema()->create(self::$table, function ($table) {
                $table->bigIncrements('id');

                $table->string('name',20)->unique();
                $table->string('slug',20)->unique();

                $table->boolean('is_active')->default(1);

                $table->unsignedBigInteger('parent_id')->nullable();
                $table->foreign('parent_id')->references('id')->on(self::$table);

                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users');

                $table->unsignedBigInteger('created_by');
                $table->foreign('created_by')->references('id')->on('users');
                
                $table->timestamps();
            });
        }
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists(self::$table);
    }
}