<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Model\Cart as CartModel;
use App\Model\CartProduct as CartProductModel;
use App\Model\Product as ProductModel;

class CartProduct
{
    private static $table = 'carts_products';

    public static function up()
    {
        if (!Capsule::schema()->hasTable(self::$table)) {
            Capsule::schema()->create(self::$table, function ($table) {
                $table->bigIncrements('id');

                $table->unsignedBigInteger('cart_id');
                $table->foreign('cart_id')->references('id')->on('carts');

                $table->unsignedBigInteger('product_id');
                $table->foreign('product_id')->references('id')->on('products');

                $table->double('price_old', 8, 2);
                $table->double('price', 8, 2);
                $table->double('qty', 8, 2);

                $table->string('observation')->nullable();
                $table->smallInteger('state')->default(1);

                $table->timestamps();
            });

            $carts = CartModel::select('id')->get()->toArray();
            $products = ProductModel::select('id','price','stock')->get()->toArray();
            
            $carts_products = [];
            foreach ($carts as $cart) {
                $count = rand(1, count($products));
                for ($i=0; $i < $count; $i++) { 
                    $product = $products[$i];
                    array_push($carts_products,[
                        'cart_id' => $cart['id'],
                        'product_id' => $product['id'],
                        'price' => rand(1, $product['price']),
                        'qty' => rand(1, $product['stock']),
                    ]);
                }
            }
            CartProductModel::insert($carts_products);

        }
    }

    public static function down()
    {
        if (Capsule::schema()->hasTable(self::$table)) {
            Capsule::schema()->dropIfExists(self::$table);
        }
    }
}