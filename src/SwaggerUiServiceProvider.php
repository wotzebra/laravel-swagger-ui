<?php

namespace NextApps\SwaggerUi;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use NextApps\SwaggerUi\Console\InstallCommand;
use NextApps\SwaggerUi\Http\Controllers\OpenApiJsonController;
use NextApps\SwaggerUi\Http\Middleware\EnsureUserIsAuthorized;

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
        Route::middleware(['web', EnsureUserIsAuthorized::class])
            ->prefix(config('swagger-ui.path'))
            ->group(function () {
                Route::view('/', 'swagger-ui::index')->name('swagger-ui');

                if (config('swagger-ui.mode') === 'file') {
                    Route::get('openapi.json', OpenApiJsonController::class)->name('swagger-openapi-json');
                }
            });
    }
}
