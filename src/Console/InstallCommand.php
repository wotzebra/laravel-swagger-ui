<?php

namespace Wotz\SwaggerUi\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class InstallCommand extends Command
{
    protected $signature = 'swagger-ui:install';

    protected $description = 'Install all of the Swagger UI resources';

    public function handle() : void
    {
        $this->comment('Publishing Swagger UI Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'swagger-ui-provider']);

        $this->comment('Publishing Swagger UI Config...');
        $this->callSilent('vendor:publish', ['--tag' => 'swagger-ui-config']);

        $this->registerSwaggerUiServiceProvider();

        $this->info('Swagger UI scaffolding installed successfully.');
    }

    protected function registerSwaggerUiServiceProvider() : void
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace . '\\Providers\\SwaggerUiServiceProvider::class')) {
            return;
        }

        $lineEndingCount = [
            "\r\n" => substr_count($appConfig, "\r\n"),
            "\r" => substr_count($appConfig, "\r"),
            "\n" => substr_count($appConfig, "\n"),
        ];

        $eol = array_keys($lineEndingCount, max($lineEndingCount))[0];

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\RouteServiceProvider::class," . $eol,
            "{$namespace}\\Providers\RouteServiceProvider::class," . $eol . "        {$namespace}\Providers\SwaggerUiServiceProvider::class," . $eol,
            $appConfig
        ));

        file_put_contents(app_path('Providers/SwaggerUiServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/SwaggerUiServiceProvider.php'))
        ));
    }
}
