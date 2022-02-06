<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Model\ProductCategory as ProductCategoryModel;
use App\Model\Product as ProductModel;
use App\Model\User;

class Product
{
    private static $table = 'products';

    public static function up()
    {
        if (!Capsule::schema()->hasTable(self::$table)) {
            Capsule::schema()->create(self::$table, function ($table) {
                $table->bigIncrements('id');

                $table->string('name',255);
                $table->string('description',250)->nullable();
                $table->string('slug',255);

                $table->double('price', 8, 2);

                $table->smallInteger('discount_type')->default(0);
                // 1 = percentage
                // 2 = amount
                $table->double('discount_quantity', 8, 2)->nullable();

                $table->integer('stock');

                $table->string('weight',50)->nullable();
                $table->string('height',50)->nullable();
                $table->string('width',50)->nullable();
                $table->string('length',50)->nullable();

                $table->integer('likes')->default(0);

                $table->smallInteger('state')->default(1);

                $table->unsignedBigInteger('product_category_id');
                $table->foreign('product_category_id')->references('id')->on('products_categories');

                $table->unsignedBigInteger('user_id')->nullable();
                $table->foreign('user_id')->references('id')->on('users');
                
                $table->timestamps();
            });

            $users = User::pluck('id')->all();
            $products_categories = ProductCategoryModel::pluck('id')->all();

            $items = [
                [
                    'name' => 'Pizza Americana',
                    'price' => rand(30, 15000),
                    'stock' => rand(1, 100),
                    'user_id' => $users[array_rand($users)],
                    'product_category_id' => $products_categories[array_rand($products_categories)],
                ],
                [
                    'name' => 'Pizzas',
                    'price' => rand(30, 15000),
                    'stock' => rand(1, 100),
                    'user_id' => $users[array_rand($users)],
                    'product_category_id' => $products_categories[array_rand($products_categories)],
                ],
                [
                    'name' => 'Pepitos',
                    'price' => rand(30, 15000),
                    'stock' => rand(1, 100),
                    'user_id' => $users[array_rand($users)],
                    'product_category_id' => $products_categories[array_rand($products_categories)],
                ],
                [
                    'name' => 'Perros calientes',
                    'price' => rand(30, 15000),
                    'stock' => rand(1, 100),
                    'user_id' => $users[array_rand($users)],
                    'product_category_id' => $products_categories[array_rand($products_categories)],
                ],
                [
                    'name' => 'Chaufas',
                    'price' => rand(30, 15000),
                    'stock' => rand(1, 100),
                    'user_id' => $users[array_rand($users)],
                    'product_category_id' => $products_categories[array_rand($products_categories)],
                ],
            ];

            foreach ($items as $key => $item) {
                $product = new ProductModel();
                $product->name = $item['name'];
                $product->price = $item['price'];
                $product->stock = $item['stock'];
                $product->user_id = $item['user_id'];
                $product->product_category_id = $item['product_category_id'];
                
                $product->creatingCustom();

                $product->save();
            }
        }
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists(self::$table);
    }
}