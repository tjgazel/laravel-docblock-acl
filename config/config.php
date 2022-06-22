<?php

use App\Models\User;
use TJGazel\LaravelDocBlockAcl\Models\Group;
use TJGazel\LaravelDocBlockAcl\Models\Permission;

return [
    'model'           => [
        'user'       => new User(),
        'group'      => new Group(),
        'permission' => new Permission(),
    ],

    'session_error'   => 'acl_error',

    'session_success' => 'acl_success',
];
