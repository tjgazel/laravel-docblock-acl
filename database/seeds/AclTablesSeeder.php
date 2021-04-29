<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class GroupPermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table(Config::get('acl.table.permissions'))->insert([
			'resource' => 'ACL',
			'name' => 'List',
			'action' => 'TJGazel\LaravelDocBlockAcl\Http\Controllers\AclController@index'
        ]);

		DB::table(Config::get('acl.table.permissions'))->insert([
			'resource' => 'ACL',
			'name' => 'Create form',
			'action' => 'TJGazel\LaravelDocBlockAcl\Http\Controllers\AclController@create'
		]);

		DB::table(Config::get('acl.table.permissions'))->insert([
			'resource' => 'ACL',
			'name' => 'Add',
			'action' => 'TJGazel\LaravelDocBlockAcl\Http\Controllers\AclController@store'
		]);

		DB::table(Config::get('acl.table.permissions'))->insert([
			'resource' => 'ACL',
			'name' => 'Edit form',
			'action' => 'TJGazel\LaravelDocBlockAcl\Http\Controllers\AclController@edit'
		]);

		DB::table(Config::get('acl.table.permissions'))->insert([
			'resource' => 'ACL',
			'name' => 'Update',
			'action' => 'TJGazel\LaravelDocBlockAcl\Http\Controllers\AclController@update'
		]);

		DB::table(Config::get('acl.table.permissions'))->insert([
			'resource' => 'ACL',
			'name' => 'Delete',
			'action' => 'TJGazel\LaravelDocBlockAcl\Http\Controllers\AclController@destroy'
		]);
    }
}
