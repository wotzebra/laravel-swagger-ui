<?php

namespace Wotz\SwaggerUi\Tests\Mcp;

use Laravel\Mcp\Server\McpServiceProvider;
use PHPUnit\Framework\Attributes\Test;
use Wotz\SwaggerUi\Mcp\Servers\SwaggerServer;
use Wotz\SwaggerUi\Mcp\Tools\GetRequestBodyTool;
use Wotz\SwaggerUi\Tests\TestCase;

class GetRequestBodyToolTest extends TestCase
{
    protected function getPackageProviders($app) : array
    {
        return [McpServiceProvider::class];
    }

    #[Test]
    public function it_retrieves_a_specific_request_body_for_a_valid_swagger_file()
    {
        SwaggerServer::tool(GetRequestBodyTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'v1',
            'requestBody' => 'UserCreate',
        ])->assertStructuredContent([
            'description' => 'User creation request body',
            'required' => true,
            'content' => [
                'application/json' => [
                    'schema' => [
                        '$ref' => '#/components/schemas/User',
                    ],
                ],
            ],
        ]);
    }

    #[Test]
    public function it_returns_error_for_nonexistent_request_body()
    {
        SwaggerServer::tool(GetRequestBodyTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'v1',
            'requestBody' => 'NonExistentRequestBody',
        ])->assertHasErrors(['Request body not found']);
    }

    #[Test]
    public function it_returns_error_for_nonexistent_file()
    {
        SwaggerServer::tool(GetRequestBodyTool::class, [
            'filename' => 'invalid-filename',
            'version' => 'v1',
            'requestBody' => 'UserCreate',
        ])->assertHasErrors(['Swagger file not found']);
    }

    #[Test]
    public function it_returns_error_for_invalid_version()
    {
        SwaggerServer::tool(GetRequestBodyTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'invalid-version',
            'requestBody' => 'UserCreate',
        ])->assertHasErrors(['Swagger file not found']);
    }
}
