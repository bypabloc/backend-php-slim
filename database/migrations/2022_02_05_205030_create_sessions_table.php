<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    private $table = 'sessions';

    public function up()
    {
        Schema::connection('mongodb')->create($this->table, function (Blueprint $table) {
            $table->string('token');
            $table->bigInteger('user_id');
            $table->timestamp('expired_at', $precision = 0);

            $table->timestamp('created_at', $precision = 0)->default(DB::raw('NOW()'));
            $table->timestamp('updated_at', $precision = 0)->nullable();
            $table->timestamp('deleted_at', $precision = 0)->nullable();
        });
    }

    public function down()
    {
        Schema::connection('mongodb')->dropIfExists($this->table);
    }
}
