<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

use App\Model\User;
use App\Model\Cart as CartModel;

class Cart
{
    private static $table = 'carts';

    public static function up()
    {
        if (!Capsule::schema()->hasTable(self::$table)) {
            Capsule::schema()->create(self::$table, function ($table) {
                $table->bigIncrements('id');

                $table->unsignedBigInteger('user_id');
                $table->foreign('user_id')->references('id')->on('users');

                $table->double('price', 8, 2);

                $table->string('observation')->nullable();

                $table->string('address')->nullable();

                $table->smallInteger('state')->default(1);
                
                $table->timestamps();
            });

            $users = User::pluck('id')->all();

            $items = [];
            for ($i=0; $i < 100; $i++) {
                array_push($items, [
                    'price' => rand(1, 10),
                    'user_id' => $users[array_rand($users)],
                ]);
            }
            CartModel::insert($items);

        }
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists(self::$table);
    }
}