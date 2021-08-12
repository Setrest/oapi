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
        $configPath = __DIR__.'/../config/l5-swagger.php';
        $this->mergeConfigFrom($configPath, 'l5-swagger');

        $this->app->singleton('command.l5-swagger.generate', function ($app) {
            return $app->make(GenerateDocsCommand::class);
        });

        $this->app->bind(Generator::class, function ($app) {
            $documentation = config('l5-swagger.default');

            $factory = $app->make(GeneratorFactory::class);

            return $factory->make($documentation);
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
