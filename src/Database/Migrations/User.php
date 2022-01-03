<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Facades\DB;

use Ramsey\Uuid\Uuid;
use App\Services\Hash;

class User
{
    public static function up()
    {
        if (!Capsule::schema()->hasTable('users')) {
            Capsule::schema()->create('users', function ($table) {
                $table->bigIncrements('id');
                $table->string('nickname')->unique();
                $table->string('email')->unique();
                
                $table->uuid('uuid')->unique();
                $table->string('password');

                $table->string('image',255)->nullable();

                $table->boolean('is_active')->default(1);
                
                $table->timestamps();
            });

            Capsule::table('users')->insert(
                [
                    [
                        'nickname' => 'admin',
                        'email' => 'admin@mail.com',
                        'uuid' => Uuid::uuid4()->toString(),
                        'password' => Hash::make('admin123'),
                    ],
                    [
                        'nickname' => 'buyer',
                        'email' => 'buyer@mail.com',
                        'uuid' => Uuid::uuid4()->toString(),
                        'password' => Hash::make('buyer123'),
                    ],
                ]
            );

        }
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists('users');
    }
}