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
class GetGeneralInfoTool extends Tool
{
    protected string $description = 'Get general information of a Swagger/OpenAPI file';

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

        return Response::structured([
            'info' => $file->json('info'),
            'servers' => $file->json('servers'),
            'components' => [
                'securitySchemes' => $file->json('components.securitySchemes'),
            ],
            'security' => $file->json('security'),
        ]);
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
