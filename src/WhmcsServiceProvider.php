<?php

namespace Sburina\Whmcs;

use Illuminate\Support\ServiceProvider;

/**
 * Whmcs ServiceProvider.
 */
class WhmcsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__) . '/config/whmcs.php' => config_path('whmcs.php'),
        ], 'whmcs-config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerWhmcs();
    }

    /**
     * Registers the WHMCS singleton instance.
     *
     * @return void
     */
    public function registerWhmcs()
    {
        // Merge the configuration
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/whmcs.php', 'whmcs');
        does this page need any6 changes to support laravel 100 & 11
        // Register the singleton
        $this->app->singleton('whmcs', function () {
            return new Whmcs();
        });

        // Register the alias
        $this->app->alias('whmcs', Whmcs::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'whmcs',
        ];
    }

}