<?php

namespace TJGazel\LaravelDocBlockAcl;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AclServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'acl');

        $this->publishes([__DIR__ . '/../config/config.php' => config_path('acl.php')], 'acl:config');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([__DIR__ . '/../database/migrations/' => database_path('migrations')], 'acl:migrations');

        $this->publishes(
            [__DIR__ . '/../database/seeds/GroupsTableSeeder.php' => database_path('seeds/GroupsTableSeeder.php')],
            'acl:seeder'
        );

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'acl');

        $this->publishes([__DIR__ . '/../resources/views' => resource_path('views/vendor/acl')], 'acl:views');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'acl');

        $this->publishes([__DIR__ . '/../resources/lang' => resource_path('lang/vendor/acl')], 'acl:translations');

        $this->regiterGates();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Acl::class, function ($app) {
            return new Acl;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Acl::class];
    }

    /**
     * @return void
     */
    private function regiterGates()
    {
        try {
            $permissionModel = Config::get('acl.model.permission');

            $permissions = $permissionModel::all();

            foreach ($permissions as $permission) {
                $name = Str::slug($permission->resource, '_') . '.' . Str::slug($permission->name, '_');

                Gate::define($name, function ($user) use ($permission) {
                    return $user->hasAclPermission($permission);
                });
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
