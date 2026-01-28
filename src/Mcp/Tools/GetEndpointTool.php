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
class GetEndpointTool extends Tool
{
    protected string $description = 'Get detailed information about a specific API endpoint within a Swagger/OpenAPI file';

    public function handle(Request $request) : Response|ResponseFactory
    {
        $request->validate([
            'filename' => 'required|string',
            'version' => 'required|string',
            'method' => 'required|string|in:GET,POST,PUT,PATCH,DELETE,HEAD,OPTIONS',
            'path' => 'required|string',
        ]);

        $file = SwaggerFile::make($request->string('filename'), $request->string('version'));

        if ($file->doesntExist()) {
            return Response::error('Swagger file not found.');
        }

        $endpoint = $file->json("paths.{$request->string('path')}.{$request->str('method')->lower()}");

        if ($endpoint === null) {
            return Response::error('Endpoint not found.');
        }

        return Response::structured($endpoint);
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
            'method' => $schema
                ->string()
                ->enum(['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'])
                ->description('HTTP method')
                ->required(),
            'path' => $schema
                ->string()
                ->description('API endpoint path (e.g., /api/users)')
                ->required(),
        ];
    }
}
