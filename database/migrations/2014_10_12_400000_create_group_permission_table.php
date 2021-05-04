<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

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

        Schema::create(Config::get('acl.table.group_permission'), function (Blueprint $table) use ($driver) {
            if ($driver == 'mysql') {
                $table->engine = 'InnoDB';
            }
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('permission_id');

            $table->foreign('group_id')->references('id')->on(Config::get('acl.table.groups'));
            $table->foreign('permission_id')->references('id')->on(Config::get('acl.table.permissions'));

            $table->unique(['group_id', 'permission_id'], 'group_permission_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Config::get('acl.table.group_permission'));
    }
}
