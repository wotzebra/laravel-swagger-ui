<?php

namespace Wotz\SwaggerUi\Tests\Mcp;

use Laravel\Mcp\Server\McpServiceProvider;
use PHPUnit\Framework\Attributes\Test;
use Wotz\SwaggerUi\Mcp\Servers\SwaggerServer;
use Wotz\SwaggerUi\Mcp\Tools\ListSchemasTool;
use Wotz\SwaggerUi\Tests\TestCase;

class ListSchemasToolTest extends TestCase
{
    protected function getPackageProviders($app) : array
    {
        return [McpServiceProvider::class];
    }

    #[Test]
    public function it_lists_all_schemas_for_a_valid_swagger_file()
    {
        SwaggerServer::tool(ListSchemasTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'v1',
        ])->assertStructuredContent([
            'schemas' => [
                'User',
                'Booking',
            ],
        ]);
    }

    #[Test]
    public function it_returns_error_for_nonexistent_file()
    {
        SwaggerServer::tool(ListSchemasTool::class, [
            'filename' => 'invalid-filename',
            'version' => 'v1',
        ])->assertHasErrors(['Swagger file not found']);
    }

    #[Test]
    public function it_returns_error_for_invalid_version()
    {
        SwaggerServer::tool(ListSchemasTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'invalid-version',
        ])->assertHasErrors(['Swagger file not found']);
    }
}
