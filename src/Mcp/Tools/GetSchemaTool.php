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
class GetSchemaTool extends Tool
{
    protected string $description = 'Get detailed information about a specific schema definition within a Swagger/OpenAPI file';

    public function handle(Request $request) : Response|ResponseFactory
    {
        $request->validate([
            'filename' => 'required|string',
            'version' => 'required|string',
            'schema' => 'required|string',
        ]);

        $file = SwaggerFile::make($request->string('filename'), $request->string('version'));

        if ($file->doesntExist()) {
            return Response::error('Swagger file not found.');
        }

        $schema = $file->json('components.schemas.' . $request->string('schema'));

        if ($schema === null) {
            return Response::error('Schema not found.');
        }

        return Response::structured($schema);
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
            'schema' => $schema
                ->string()
                ->description('Name of the schema/model to retrieve'),
        ];
    }
}
