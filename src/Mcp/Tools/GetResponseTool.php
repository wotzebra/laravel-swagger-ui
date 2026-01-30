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
class GetResponseTool extends Tool
{
    protected string $description = 'Get detailed information about a specific reusable response definition within a Swagger/OpenAPI file';

    public function handle(Request $request) : Response|ResponseFactory
    {
        $request->validate([
            'filename' => 'required|string',
            'version' => 'required|string',
            'response' => 'required|string',
        ]);

        $file = SwaggerFile::make($request->string('filename'), $request->string('version'));

        if ($file->doesntExist()) {
            return Response::error('Swagger file not found.');
        }

        $response = $file->json('components.responses.' . $request->string('response'));

        if ($response === null) {
            return Response::error('Response not found.');
        }

        return Response::structured($response);
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
            'response' => $schema
                ->string()
                ->required()
                ->description('Name of the reusable response to retrieve'),
        ];
    }
}
