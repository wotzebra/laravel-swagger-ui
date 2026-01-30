<?php

namespace Wotz\SwaggerUi\Mcp\Tools;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\ResponseFactory;
use Laravel\Mcp\Server\Tool;
use Laravel\Mcp\Server\Tools\Annotations\IsReadOnly;
use Wotz\SwaggerUi\SwaggerFile;

#[IsReadOnly]
class ListEndpointsTool extends Tool
{
    protected string $description = 'List all endpoints within a Swagger/OpenAPI file along with their HTTP methods';

    public function handle(Request $request) : Response|ResponseFactory
    {
        $request->validate([
            'filename' => 'required|string',
            'version' => 'required|string',
        ]);

        $file = SwaggerFile::make($request->string('filename'), $request->string('version'));

        if ($file->doesntExist()) {
            return Response::error('Swagger file not found.');
        }

        $endpoints = $file->collect('paths')->map(function (array $spec, string $path) {
            return collect($spec)->except('parameters')->map(function (array $spec, string $method) use ($path) {
                return [
                    'path' => $path,
                    'title' => data_get($spec, 'summary'),
                    'method' => strtoupper($method),
                ];
            })->values()->all();
        })->flatten(1)->values();

        return Response::structured(['endpoints' => $endpoints->all()]);
    }

    public function schema(JsonSchema $schema) : array
    {
        return [
            'filename' => $schema
                ->string()
                ->required()
                ->description('Swagger file name'),
            'version' => $schema
                ->string()
                ->required()
                ->description('Version of the swagger file'),
        ];
    }
}
