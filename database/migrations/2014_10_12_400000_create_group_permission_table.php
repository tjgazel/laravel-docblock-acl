<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupPermissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $connection = Config::get('database.default');
        $driver = Config::get("database.connections.{$connection}.driver");

        Schema::create('group_permission', function (Blueprint $table) use ($driver) {
            if ($driver == 'mysql') {
                $table->engine = 'InnoDB';
            }
            $table->unsignedInteger('group_id');
            $table->unsignedBigInteger('permission_id');

            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('permission_id')->references('id')->on('permissions');
			
			$table->primary(['group_id', 'permission_id'], 'group_permission_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_permission');
    }
}
