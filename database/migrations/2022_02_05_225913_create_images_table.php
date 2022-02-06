<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    private $table = 'images';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->id();

            $table->integer('table_id');

            $table->string('table_name');
            
            $table->string('path');

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
