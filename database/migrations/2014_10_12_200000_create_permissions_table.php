<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class CreatePermissionsTable extends Migration
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

        Schema::create(Config::get('acl.table.permissions'), function (Blueprint $table) use ($driver) {
            if ($driver == 'mysql') {
                $table->engine = 'InnoDB';
            }
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('resource');
            $table->string('action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(Config::get('acl.table.permissions'));
    }
}
