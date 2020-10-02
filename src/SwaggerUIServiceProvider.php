<?php

namespace NextApps\SwaggerUI;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use NextApps\SwaggerUI\OpenApiJsonController;

class SwaggerUIServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'swagger-ui');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/swagger-ui.php' => config_path('swagger-ui.php'),
            ], 'config');
        }

        Route::middleware('web')
            ->prefix(config('swagger-ui.path'))
            ->group(function () {
                Route::view('/', 'swagger-ui::index')->name('swagger-ui');

                Route::get('openapi.json', OpenApiJsonController::class)->name('swagger-openapi-json');
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/swagger-ui.php', 'swagger-ui');
    }
}
