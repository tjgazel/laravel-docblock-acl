<?php

namespace TJGazel\LaravelDocBlockAcl\Facades;

use Illuminate\Support\Facades\Facade;
use TJGazel\LaravelDocBlockAcl\Acl as LaravelAcl;

/**
 * @method static \TJGazel\LaravelDocBlockAcl\Acl routes(array $options = ['middleware' => ['auth', 'acl'], 'prefix' => 'acl', 'name' => 'acl.'])
 * @method static \TJGazel\LaravelDocBlockAcl\Acl getMiddleware()
 * @method static \TJGazel\LaravelDocBlockAcl\Acl getPrefixURL()
 * @method static \TJGazel\LaravelDocBlockAcl\Acl getPrefixRouteName()
 * @method static \TJGazel\LaravelDocBlockAcl\Acl mapPermissions()
 * @method static \TJGazel\LaravelDocBlockAcl\Acl routesWithPermission()
 *
 * @see TJGazel\LaravelDocBlockAcl\Acl
 */
class Acl extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return LaravelAcl::class;
    }
}
