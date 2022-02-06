<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Hash;

class CreateUsersTable extends Migration
{
    private $table = 'users';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nickname')->unique();
            $table->string('email')->unique();
            $table->smallInteger('sex');
            $table->date('birthday');

            $table->uuid('uuid')->unique();
            $table->string('password');

            $table->string('image',255)->nullable();

            $table->boolean('is_active')->default(1);

            $table->timestamp('created_at', $precision = 0)->default(DB::raw('NOW()'));
            $table->timestamp('updated_at', $precision = 0)->nullable();
            $table->timestamp('deleted_at', $precision = 0)->nullable();
        });

        DB::table($this->table)->insert(
            [
                [
                    'nickname' => 'admin',
                    'email' => 'admin@mail.com',
                    'uuid' => (string) Str::uuid(),
                    'password' => Hash::make('12345678'),
                    'sex'=>1,
                    'birthday'=>'1990-02-11'
                ],
                [
                    'nickname' => 'user1',
                    'email' => 'user1@mail.com',
                    'uuid' => (string) Str::uuid(),
                    'password' => Hash::make('12345678'),
                    'sex'=>2,
                    'birthday'=>'1990-02-11'
                ],
                [
                    'nickname' => 'user2',
                    'email' => 'user2@mail.com',
                    'uuid' => (string) Str::uuid(),
                    'password' => Hash::make('12345678'),
                    'sex'=>1,
                    'birthday'=>'1990-02-11'
                ],
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
