<?php

namespace RyanLHolt\Infused;

use Illuminate\Log\LogManager;
use Illuminate\Support\ServiceProvider;


class InfusedServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'infused');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'infused');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('infused.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../database/migrations/create_infusionsoft_tokens_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_infusionsoft_tokens_table.php'),
            ], 'migrations');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/infused'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/infused'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/infused'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }

        // Pass the LogManager from the Log provider into infusionsoft
        $this->app->resolving('infusionsoft', function ($infusionsoft, $app) {
            // Called when container resolves objects of type "Infusionsoft"
            $logger = $app->make(LogManager::class);

            $infusionsoft->setHttpLogAdapter($logger);
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'infused');

        // Register the main class to use with the facade
        $this->app->singleton('infused', function ($app) {
            return new Infused($app);
        });
    }
}
