<?php

namespace Setrest\OAPIDocumentation;

use Illuminate\Support\ServiceProvider;
use Setrest\OAPIDocumentation\Console\GenerateDocumentation;

class OpenAPIServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Publish a config file
        $this->publishes([
            __DIR__.'/../../config/oapidocs.php' => config_path('oapidocs.php'),
        ]);

        //Register commands
        $this->commands([GenerateDocumentation::class]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.oapi.generate', function ($app) {
            return $app->make(GenerateDocumentation::class);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.oapi.generate',
        ];
    }
}
