<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class CreateGroupUserTable extends Migration
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

        Schema::create(Config::get('acl.table.group_user'), function (Blueprint $table) use ($driver) {
            if ($driver == 'mysql') {
                $table->engine = 'InnoDB';
            }

            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('user_id');

            $table->foreign('group_id')->references('id')->on(Config::get('acl.table.groups'));
            $table->foreign('user_id')->references('id')->on(Config::get('acl.table.users'));

            $table->unique(['group_id', 'user_id'], 'group_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Config::get('acl.table.group_user'));
    }
}
