<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class RolePermission
{
    public static function up()
    {
        if (!Capsule::schema()->hasTable('roles_permissions')) {
            Capsule::schema()->create('roles_permissions', function ($table) {
                $table->bigIncrements('id');

                $table->unsignedBigInteger('role_id');
                $table->foreign('role_id')->references('id')->on('roles');

                $table->unsignedBigInteger('permission_id');
                $table->foreign('permission_id')->references('id')->on('permissions');

                $table->unsignedBigInteger('created_by');
                $table->foreign('created_by')->references('id')->on('users');

                $table->timestamps();
            });
        }
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists('roles_permissions');
    }
}