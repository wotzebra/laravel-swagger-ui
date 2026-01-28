<?php

namespace Wotz\SwaggerUi\Tests\Mcp;

use Laravel\Mcp\Server\McpServiceProvider;
use Wotz\SwaggerUi\Mcp\Servers\SwaggerServer;
use Wotz\SwaggerUi\Mcp\Tools\ListEndpointsTool;
use Wotz\SwaggerUi\Tests\TestCase;

class ListEndpointsToolTest extends TestCase
{
    protected function getPackageProviders($app) : array
    {
        return [McpServiceProvider::class];
    }

    /** @test */
    public function it_lists_all_endpoints_for_a_valid_swagger_file()
    {
        SwaggerServer::tool(ListEndpointsTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'v1',
        ])->assertStructuredContent([
            [
                'path' => '/user/{userId}',
                'title' => 'Get user by ID',
                'method' => 'GET',
            ],
            [
                'path' => '/user/{userId}',
                'title' => 'Update user by ID',
                'method' => 'PUT',
            ],
            [
                'path' => '/booking/{bookingId}',
                'title' => 'Get booking by ID',
                'method' => 'GET',
            ],
        ]);
    }

    /** @test */
    public function it_returns_error_for_nonexistent_file()
    {
        SwaggerServer::tool(ListEndpointsTool::class, [
            'filename' => 'invalid-filename',
            'version' => 'v1',
        ])->assertHasErrors(['Swagger file not found']);
    }

    /** @test */
    public function it_returns_error_for_invalid_version()
    {
        SwaggerServer::tool(ListEndpointsTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'invalid-version',
        ])->assertHasErrors(['Swagger file not found']);
    }
}
