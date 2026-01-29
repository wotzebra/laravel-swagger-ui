<?php

namespace Wotz\SwaggerUi\Mcp\Tools;

use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;

#[IsReadOnly]
class ListSwaggerFilesTool extends Tool
{
    protected string $description = 'List all available Swagger/OpenAPI files and their versions configured in the application';

    public function handle() : Response|ResponseFactory
    {
        $files = collect(config('swagger-ui.files'))->map(fn ($file) => [
            'filename' => $file['path'],
            'versions' => array_keys($file['versions']),
        ])->all();

        return Response::structured($files);
    }
}
