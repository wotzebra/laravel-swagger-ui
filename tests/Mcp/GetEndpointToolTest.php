<?php

namespace Wotz\SwaggerUi\Tests\Mcp;

use Laravel\Mcp\Server\McpServiceProvider;
use PHPUnit\Framework\Attributes\Test;
use Wotz\SwaggerUi\Mcp\Servers\SwaggerServer;
use Wotz\SwaggerUi\Mcp\Tools\GetEndpointTool;
use Wotz\SwaggerUi\Tests\TestCase;

class GetEndpointToolTest extends TestCase
{
    protected function getPackageProviders($app) : array
    {
        return [McpServiceProvider::class];
    }

    #[Test]
    public function it_retrieves_a_specific_endpoint_for_a_valid_swagger_file()
    {
        SwaggerServer::tool(GetEndpointTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'v1',
            'method' => 'GET',
            'path' => '/user/{userId}',
        ])->assertStructuredContent([
            'summary' => 'Get user by ID',
            'tags' => [],
            'responses' => [
                '200' => [
                    'description' => 'OK',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/User',
                            ],
                        ],
                    ],
                ],
            ],
            'operationId' => 'get-user-userId',
        ]);
    }

    #[Test]
    public function it_retrieves_an_endpoint_with_different_http_method()
    {
        SwaggerServer::tool(GetEndpointTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'v1',
            'method' => 'PUT',
            'path' => '/user/{userId}',
        ])->assertStructuredContent([
            'summary' => 'Update user by ID',
            'tags' => [],
            'responses' => [
                '200' => [
                    'description' => 'OK',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/User',
                            ],
                        ],
                    ],
                ],
            ],
            'operationId' => 'put-user-userId',
        ]);
    }

    #[Test]
    public function it_returns_error_for_nonexistent_file()
    {
        SwaggerServer::tool(GetEndpointTool::class, [
            'filename' => 'invalid-filename',
            'version' => 'v1',
            'method' => 'GET',
            'path' => '/user/{userId}',
        ])->assertHasErrors(['Swagger file not found']);
    }

    #[Test]
    public function it_returns_error_for_invalid_version()
    {
        SwaggerServer::tool(GetEndpointTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'invalid-version',
            'method' => 'GET',
            'path' => '/user/{userId}',
        ])->assertHasErrors(['Swagger file not found']);
    }

    #[Test]
    public function it_returns_error_for_nonexistent_endpoint()
    {
        SwaggerServer::tool(GetEndpointTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'v1',
            'method' => 'GET',
            'path' => '/nonexistent/path',
        ])->assertHasErrors(['Endpoint not found']);
    }

    #[Test]
    public function it_returns_error_for_nonexistent_method_on_existing_path()
    {
        SwaggerServer::tool(GetEndpointTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'v1',
            'method' => 'DELETE',
            'path' => '/user/{userId}',
        ])->assertHasErrors(['Endpoint not found']);
    }
}
