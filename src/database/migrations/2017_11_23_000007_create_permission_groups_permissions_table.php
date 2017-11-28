<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionGroupsPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_groups_permissions', function (Blueprint $table) {
            $table->integer('permission_group_id')->unsigned();
            $table->integer('permission_id')->unsigned();

            $table->foreign('permission_group_id', 'fk_pgp_group_id')
                ->references('id')
                ->on('permission_groups')
                ->onDelete('cascade');
            
            $table->foreign('permission_id', 'fk_pgp_permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('cascade');

            $table->primary(['permission_group_id', 'permission_id'], 'fk_pgp_group_id_permission_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permission_groups_permissions');
    }
}
