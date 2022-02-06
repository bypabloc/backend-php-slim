<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiscountsTable extends Migration
{
    private $table = 'discounts';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->string('coupon');
            
            $table->smallInteger('discount_type')->default(1);
            // 1 = percentage
            // 2 = amount
            $table->double('discount_quantity', 8, 2);
            
            $table->double('mount_max_discount', 8, 2)->nullable();
            
            $table->boolean('is_active')->default(1);
            
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users');
            
            $table->timestamp('expired_at', $precision = 0);

            $table->timestamp('created_at', $precision = 0)->default(DB::raw('NOW()'));
            $table->timestamp('updated_at', $precision = 0)->nullable();
            $table->timestamp('deleted_at', $precision = 0)->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
