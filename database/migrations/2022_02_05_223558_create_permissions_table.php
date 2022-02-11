<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePermissionsTable extends Migration
{
    private $table = 'permissions';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->unique();
            $table->string('alias')->unique();
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
                    'name' => 'products',
                    'alias' => 'products',
                    'created_by' => 1,
                ],
                [
                    'name' => 'carts',
                    'alias' => 'carts',
                    'created_by' => 1,
                ],
                [
                    'name' => 'users',
                    'alias' => 'users',
                    'created_by' => 1,
                ],
                [
                    'name' => 'products_categories',
                    'alias' => 'products_categories',
                    'created_by' => 1,
                ],
                [
                    'name' => 'products_reviews',
                    'alias' => 'products_reviews',
                    'created_by' => 1,
                ],
                [
                    'name' => 'roles',
                    'alias' => 'roles',
                    'created_by' => 1,
                ],
                [
                    'name' => 'permissions',
                    'alias' => 'permissions',
                    'created_by' => 1,
                ],
                [
                    'name' => 'migrations',
                    'alias' => 'migrations',
                    'created_by' => 1,
                ],
                [
                    'name' => 'carts.get_all.admin',
                    'alias' => 'carts.get_all.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'carts.find.admin',
                    'alias' => 'carts.find.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'carts.create.admin',
                    'alias' => 'carts.create.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'carts.update.admin',
                    'alias' => 'carts.update.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'carts.state.admin',
                    'alias' => 'carts.state.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'products_categories.get_all.admin',
                    'alias' => 'products_categories.get_all.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'products_categories.find.admin',
                    'alias' => 'products_categories.find.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'products_categories.create.admin',
                    'alias' => 'products_categories.create.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'products_categories.update.admin',
                    'alias' => 'products_categories.update.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'products_categories.state.admin',
                    'alias' => 'products_categories.state.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'products_reviews.create.admin',
                    'alias' => 'products_reviews.create.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'products_reviews.update.admin',
                    'alias' => 'products_reviews.update.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'products_reviews.state.admin',
                    'alias' => 'products_reviews.state.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'products.get_all.admin',
                    'alias' => 'products.get_all.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'products.find.admin',
                    'alias' => 'products.find.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'products.create.admin',
                    'alias' => 'products.create.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'products.update.admin',
                    'alias' => 'products.update.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'products.state.admin',
                    'alias' => 'products.state.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'products.request.admin',
                    'alias' => 'products.request.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'products.to_pay.admin',
                    'alias' => 'products.to_pay.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'discounts',
                    'alias' => 'discounts',
                    'created_by' => 1,
                ],
                [
                    'name' => 'discounts.get_all.admin',
                    'alias' => 'discounts.get_all.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'discounts.create.admin',
                    'alias' => 'discounts.create.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'discounts.find.admin',
                    'alias' => 'discounts.find.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'discounts.state.admin',
                    'alias' => 'discounts.state.admin',
                    'created_by' => 1,
                ],
                [
                    'name' => 'discounts.update.admin',
                    'alias' => 'discounts.update.admin',
                    'created_by' => 1,
                ],
            ],
        );
    }

    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
