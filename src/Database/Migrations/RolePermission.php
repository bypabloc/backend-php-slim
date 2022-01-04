<?php

namespace App\Database\Migrations;

use Illuminate\Database\Capsule\Manager as Capsule;

class RolePermission
{
    private static $table = 'roles_permissions';

    public static function up()
    {
        if (!Capsule::schema()->hasTable(self::$table)) {
            Capsule::schema()->create(self::$table, function ($table) {
                $table->bigIncrements('id');

                $table->unsignedBigInteger('role_id');
                $table->foreign('role_id')->references('id')->on('roles');

                $table->unsignedBigInteger('permission_id');
                $table->foreign('permission_id')->references('id')->on('permissions');

                $table->timestamps();
            });

            $permissions = Capsule::table('permissions')->pluck('id')->toArray();
            $roles_permissions = [];
            foreach ($permissions as $permission) {
                $roles_permissions[] = [
                    'role_id' => 1,
                    'permission_id' => $permission,
                ];
            }
            $roles_permissions[] = [
                'role_id' => 2,
                'permission_id' => 1,
            ];
            $roles_permissions[] = [
                'role_id' => 2,
                'permission_id' => 2,
            ];
            $roles_permissions[] = [
                'role_id' => 2,
                'permission_id' => 3,
            ];
            Capsule::table(self::$table)->insert($roles_permissions);
        }
    }

    public static function down()
    {
        Capsule::schema()->dropIfExists(self::$table);
    }
}