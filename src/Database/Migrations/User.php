<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\DB;

use Ramsey\Uuid\Uuid;
use App\Services\Hash;

class User
{
    private static $table = 'users';

    public static function up()
    {
        if (!Capsule::schema()->hasTable(self::$table)) {
            Capsule::schema()->create(self::$table, function ($table) {
                $table->bigIncrements('id');
                $table->string('nickname')->unique();
                $table->string('email')->unique();
                
                $table->uuid('uuid')->unique();
                $table->string('password');

                $table->string('image',255)->nullable();

                $table->boolean('is_active')->default(1);
                
                $table->timestamps();
            });

            Capsule::table(self::$table)->insert(
                [
                    [
                        'nickname' => 'admin',
                        'email' => 'admin@mail.com',
                        'uuid' => Uuid::uuid4()->toString(),
                        'password' => Hash::make('12345678'),
                    ],
                    [
                        'nickname' => 'user1',
                        'email' => 'user1@mail.com',
                        'uuid' => Uuid::uuid4()->toString(),
                        'password' => Hash::make('12345678'),
                    ],
                    [
                        'nickname' => 'user2',
                        'email' => 'user2@mail.com',
                        'uuid' => Uuid::uuid4()->toString(),
                        'password' => Hash::make('12345678'),
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