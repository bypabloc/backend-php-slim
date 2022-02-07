<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSessionsTable extends Migration
{
    protected $connection = 'mongodb';
    public function __construct()
    {
        $this->connection = config('app.env') === 'testing' ? 'mongodb_test' : 'mongodb';
    }

    private $table = 'sessions';

    public function up()
    {
        Schema::connection($this->connection)->create($this->table, function (Blueprint $table) {
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
        Schema::connection($this->connection)->dropIfExists($this->table);
    }
}
