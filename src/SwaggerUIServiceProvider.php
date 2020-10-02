<?php

namespace NextApps\SwaggerUI;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

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

                Route::get('openapi.json', function () {
                    $json = file_get_contents(config('swagger-ui.file'));
                    $json = json_decode($json, true);

                    $json['schemes'] = [parse_url(config('app.url'), PHP_URL_SCHEME)];
                    $json['host'] = str_replace($json['schemes'][0], '', config('app.url'));

                    return $json;
                })->name('swagger-openapi-json');
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
