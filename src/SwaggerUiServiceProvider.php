<?php

namespace Wotz\SwaggerUi;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Wotz\SwaggerUi\Console\InstallCommand;
use Wotz\SwaggerUi\Http\Controllers\OpenApiJsonController;
use Wotz\SwaggerUi\Http\Controllers\SwaggerOAuth2RedirectController;
use Wotz\SwaggerUi\Http\Controllers\SwaggerViewController;

class SwaggerUiServiceProvider extends ServiceProvider
{
    public function boot() : void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'swagger-ui');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/swagger-ui.php' => config_path('swagger-ui.php'),
            ], 'swagger-ui-config');

            $this->publishes([
                __DIR__ . '/../stubs/SwaggerUiServiceProvider.stub' => app_path('Providers/SwaggerUiServiceProvider.php'),
            ], 'swagger-ui-provider');
        }

        $this->loadRoutes();
    }

    public function register() : void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/swagger-ui.php', 'swagger-ui');

        $this->commands([InstallCommand::class]);
    }

    protected function loadRoutes() : void
    {
        collect(config('swagger-ui.files'))->each(function ($values) {
            Route::middleware($values['middleware'])
                ->group(function () use ($values) {
                    Route::get($values['path'], SwaggerViewController::class)->name($values['path'] . '.index');
                    Route::get($values['path'] . '/oauth2-redirect', SwaggerOAuth2RedirectController::class)->name($values['path'] . '.oauth2-redirect');
                    Route::get($values['path'] . '/{filename}', OpenApiJsonController::class)->name($values['path'] . '.json');
                });
        });
    }
}
