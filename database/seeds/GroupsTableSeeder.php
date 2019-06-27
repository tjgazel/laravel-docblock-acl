<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            'name' => 'Admin'
        ]);
        DB::table('groups')->insert([
            'name' => 'Manager'
        ]);
        DB::table('groups')->insert([
            'name' => 'Client'
        ]);
    }
}
