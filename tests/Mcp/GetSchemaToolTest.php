<?php

namespace Wotz\SwaggerUi\Tests\Mcp;

use Laravel\Mcp\Server\McpServiceProvider;
use Wotz\SwaggerUi\Mcp\Servers\SwaggerServer;
use Wotz\SwaggerUi\Mcp\Tools\GetSchemaTool;
use Wotz\SwaggerUi\Tests\TestCase;

class GetSchemaToolTest extends TestCase
{
    protected function getPackageProviders($app) : array
    {
        return [McpServiceProvider::class];
    }

    /** @test */
    public function it_retrieves_a_specific_schema_for_a_valid_swagger_file()
    {
        SwaggerServer::tool(GetSchemaTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'v1',
            'schema' => 'Booking',
        ])->assertStructuredContent([
            'title' => 'Booking Model Title',
            'type' => 'object',
            'properties' => [
                'id' => [
                    'type' => 'string',
                ],
                'status' => [
                    'type' => 'string',
                ],
            ],
            'required' => [
                'id',
                'status',
            ],
        ]);
    }

    /** @test */
    public function it_returns_error_for_nonexistent_file()
    {
        SwaggerServer::tool(GetSchemaTool::class, [
            'filename' => 'invalid-filename',
            'version' => 'v1',
            'schema' => 'Booking',
        ])->assertHasErrors(['Swagger file not found']);
    }

    /** @test */
    public function it_returns_error_for_invalid_version()
    {
        SwaggerServer::tool(GetSchemaTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'invalid-version',
            'schema' => 'Booking',
        ])->assertHasErrors(['Swagger file not found']);
    }

    /** @test */
    public function it_returns_error_for_nonexistent_schema()
    {
        SwaggerServer::tool(GetSchemaTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'v1',
            'schema' => 'NonExistentSchema',
        ])->assertHasErrors(['Schema not found']);
    }
}
