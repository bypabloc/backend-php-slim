<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsConfigsTable extends Migration
{
    private $table = 'discounts_configs';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->integer('table_id');

            $table->string('table_name');

            $table->unsignedBigInteger('discount_id');
            $table->foreign('discount_id')->references('id')->on('discounts');

            $table->timestamp('created_at', $precision = 0)->default(DB::raw('NOW()'));
            $table->timestamp('updated_at', $precision = 0)->nullable();
            $table->timestamp('deleted_at', $precision = 0)->nullable();
        });
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
