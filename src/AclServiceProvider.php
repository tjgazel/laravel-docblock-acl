<?php

namespace TJGazel\LaravelDocBlockAcl;

use Illuminate\Support\ServiceProvider;

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

        $this->publishes([__DIR__ . '/../config/config.php' => config_path('acl.php')], 'acl-config');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->publishes([__DIR__ . '/../database/migrations/' => database_path('migrations')], 'acl-migrations');

        $this->publishes([__DIR__ . '/../database/seeds/' => database_path('seeds')], 'acl-seeds');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'acl');

        $this->publishes([__DIR__ . '/../resources/views' => resource_path('views/vendor/acl')], 'acl-views');

        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'acl');

        $this->publishes([__DIR__ . '/../resources/lang' => resource_path('lang/vendor/acl')], 'acl-translations');
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
}
