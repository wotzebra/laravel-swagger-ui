<?php

namespace Wotz\SwaggerUi\Tests\Mcp;

use Laravel\Mcp\Server\McpServiceProvider;
use Wotz\SwaggerUi\Mcp\Servers\SwaggerServer;
use Wotz\SwaggerUi\Mcp\Tools\ListResponsesTool;
use Wotz\SwaggerUi\Tests\TestCase;

class ListResponsesToolTest extends TestCase
{
    protected function getPackageProviders($app) : array
    {
        return [McpServiceProvider::class];
    }

    /** @test */
    public function it_lists_all_responses_for_a_valid_swagger_file()
    {
        SwaggerServer::tool(ListResponsesTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'v1',
        ])->assertStructuredContent([
            'responses' => [
                'NotFound',
                'Unauthorized',
            ],
        ]);
    }

    /** @test */
    public function it_returns_error_for_nonexistent_file()
    {
        SwaggerServer::tool(ListResponsesTool::class, [
            'filename' => 'invalid-filename',
            'version' => 'v1',
        ])->assertHasErrors(['Swagger file not found']);
    }

    /** @test */
    public function it_returns_error_for_invalid_version()
    {
        SwaggerServer::tool(ListResponsesTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'invalid-version',
        ])->assertHasErrors(['Swagger file not found']);
    }
}
