<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersForeignColumn extends Migration
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

        Schema::table('users', function (Blueprint $table) use ($driver) {
            if ($driver == 'mysql') {
                DB::statement('ALTER TABLE users ENGINE = InnoDB');
            }
            $table->unsignedInteger('group_id')->nullable()->after('id');

            $table->foreign('group_id')->references('id')->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('group_id');

            $table->dropColumn('group_id');
        });
        Schema::enableForeignKeyConstraints();
    }
}
