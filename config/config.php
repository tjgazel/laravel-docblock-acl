<?php

return [
    'model' => [
        'user' => '\App\Models\User',
        'group' => '\TJGazel\LaravelDocBlockAcl\Models\Group',
        'permission' => '\TJGazel\LaravelDocBlockAcl\Models\Permission',
    ],

    'table' => [
        'users' => 'users',
        'groups' => 'groups',
        'permissions' => 'permissions',
        'group_permission' => 'group_permission',
        'group_user' => 'group_user',
    ],

    'session_error' => 'acl_error',

    'session_success' => 'acl_success',
];
