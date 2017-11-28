<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionGroupsRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_groups_roles', function (Blueprint $table) {
            $table->integer('permission_group_id')->unsigned();
            $table->integer('role_id')->unsigned();
            
            $table->foreign('permission_group_id', 'fk_pgr_group_id')
                ->references('id')
                ->on('permission_groups')
                ->onDelete('cascade');

            $table->foreign('role_id', 'fk_pgr_role_id')
                ->references('id')
                ->on('roles')
                ->onDelete('cascade');

            $table->primary(['permission_group_id', 'role_id'], 'fk_pgr_group_id_role_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_groups_roles');
    }
}
