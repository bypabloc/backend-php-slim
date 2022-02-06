<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    private $table = 'products';
    
    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name',255);
            $table->string('description',250)->nullable();
            $table->string('slug',255);

            $table->double('price', 8, 2);

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
