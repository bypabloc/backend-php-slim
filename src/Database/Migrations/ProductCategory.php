<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Model\ProductCategory as ProductCategoryModel;
use App\Model\User;

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

            $users = User::pluck('id')->all();

            $items = [
                [
                    'name' => 'Hamburguesas',
                    'user_id' => $users[array_rand($users)],
                ],
                [
                    'name' => 'Pizzas',
                    'user_id' => $users[array_rand($users)],
                ],
                [
                    'name' => 'Pepitos',
                    'user_id' => $users[array_rand($users)],
                ],
                [
                    'name' => 'Perros calientes',
                    'user_id' => $users[array_rand($users)],
                ],
                [
                    'name' => 'Chaufas',
                    'user_id' => $users[array_rand($users)],
                ],
            ];

            foreach ($items as $key => $item) {
                $product_category = new ProductCategoryModel();
                $product_category->name = $item['name'];
                $product_category->is_active = 1;
                // $product_category->parent_id = $item['parent_id'];
                $product_category->user_id = $item['user_id'];
                $product_category->created_by = 1;

                $product_category->creatingCustom();

                $product_category->save();
            }
        }
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists(self::$table);
    }
}