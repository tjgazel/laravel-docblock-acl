<?php

return [
    'model'           => [
        'user'       => '\App\User',
        'group'      => '\TJGazel\LaravelDocBlockAcl\Models\Group',
        'permission' => '\TJGazel\LaravelDocBlockAcl\Models\Permission',
    ],

    'session_error'   => 'acl_error',

    'session_success' => 'acl_success',
];
