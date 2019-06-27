<?php

namespace TJGazel\LaravelDocBlockAcl\Facades;

use Illuminate\Support\Facades\Facade;
use TJGazel\LaravelDocBlockAcl\Acl as LaravelAcl;

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
