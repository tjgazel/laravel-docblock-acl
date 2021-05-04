<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

/**
 * Class AclTablesSeeder
 */
class AclTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->groupsSeeder();
        $this->permissionsSeeder();
        $this->adminPermissionsSeeder();
    }

    private function groupsSeeder()
    {
        DB::table(Config::get('acl.table.groups'))->insert([
            ['name' => 'Admin', 'description' => 'Group for Admin'],
            ['name' => 'Manager', 'description' => 'Group for Manager'],
            ['name' => 'Client', 'description' => 'Group for Client'],
        ]);
    }

    private function permissionsSeeder()
    {
        DB::table(Config::get('acl.table.permissions'))->insert([
            'resource' => 'ACL',
            'name' => 'List',
            'action' => 'TJGazel\LaravelDocBlockAcl\Http\Controllers\AclController@index',
        ]);

        DB::table(Config::get('acl.table.permissions'))->insert([
            'resource' => 'ACL',
            'name' => 'Create form',
            'action' => 'TJGazel\LaravelDocBlockAcl\Http\Controllers\AclController@create',
        ]);

        DB::table(Config::get('acl.table.permissions'))->insert([
            'resource' => 'ACL',
            'name' => 'Add',
            'action' => 'TJGazel\LaravelDocBlockAcl\Http\Controllers\AclController@store',
        ]);

        DB::table(Config::get('acl.table.permissions'))->insert([
            'resource' => 'ACL',
            'name' => 'Edit form',
            'action' => 'TJGazel\LaravelDocBlockAcl\Http\Controllers\AclController@edit',
        ]);

        DB::table(Config::get('acl.table.permissions'))->insert([
            'resource' => 'ACL',
            'name' => 'Update',
            'action' => 'TJGazel\LaravelDocBlockAcl\Http\Controllers\AclController@update',
        ]);

        DB::table(Config::get('acl.table.permissions'))->insert([
            'resource' => 'ACL',
            'name' => 'Delete',
            'action' => 'TJGazel\LaravelDocBlockAcl\Http\Controllers\AclController@destroy',
        ]);
    }

    private function adminPermissionsSeeder()
    {
        DB::table(Config::get('acl.table.group_permission'))->insert([
            ['group_id' => 1, 'permission_id' => 1],
            ['group_id' => 1, 'permission_id' => 2],
            ['group_id' => 1, 'permission_id' => 3],
            ['group_id' => 1, 'permission_id' => 4],
            ['group_id' => 1, 'permission_id' => 5],
            ['group_id' => 1, 'permission_id' => 6],
        ]);
    }
}
