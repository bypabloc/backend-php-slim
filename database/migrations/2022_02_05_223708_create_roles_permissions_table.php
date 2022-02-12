<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRolesPermissionsTable extends Migration
{
    private $table = 'roles_permissions';

    public function up()
    {
        Schema::create($this->table, function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles');

            $table->unsignedBigInteger('permission_id');
            $table->foreign('permission_id')->references('id')->on('permissions');

            $table->timestamp('created_at', $precision = 0)->default(DB::raw('NOW()'));
            $table->timestamp('updated_at', $precision = 0)->nullable();
            $table->timestamp('deleted_at', $precision = 0)->nullable();
        });

        $permissions = DB::table('permissions')->pluck('id')->toArray();
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
        $roles_permissions[] = [
            'role_id' => 2,
            'permission_id' => 4,
        ];
        $roles_permissions[] = [
            'role_id' => 2,
            'permission_id' => 5,
        ];
        DB::table($this->table)->insert($roles_permissions);
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
