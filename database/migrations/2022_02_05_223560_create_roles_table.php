<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateRolesTable extends Migration
{
    private $table = 'roles';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->boolean('is_active')->default(1);

            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');

            $table->timestamp('created_at', $precision = 0)->default(DB::raw('NOW()'));
            $table->timestamp('updated_at', $precision = 0)->nullable();
            $table->timestamp('deleted_at', $precision = 0)->nullable();
        });

        DB::table($this->table)->insert(
            [
                [
                    'name' => 'admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'user',
                    'created_by' => 1,
                ],
            ]
        );
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
