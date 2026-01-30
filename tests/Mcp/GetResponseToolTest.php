<?php

namespace Wotz\SwaggerUi\Tests\Mcp;

use Laravel\Mcp\Server\McpServiceProvider;
use PHPUnit\Framework\Attributes\Test;
use Wotz\SwaggerUi\Mcp\Servers\SwaggerServer;
use Wotz\SwaggerUi\Mcp\Tools\GetResponseTool;
use Wotz\SwaggerUi\Tests\TestCase;

class GetResponseToolTest extends TestCase
{
    protected function getPackageProviders($app) : array
    {
        return [McpServiceProvider::class];
    }

    #[Test]
    public function it_retrieves_a_specific_response_for_a_valid_swagger_file()
    {
        SwaggerServer::tool(GetResponseTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'v1',
            'response' => 'NotFound',
        ])->assertStructuredContent([
            'description' => 'The specified resource was not found',
            'content' => [
                'application/json' => [
                    'schema' => [
                        'type' => 'object',
                        'properties' => [
                            'message' => [
                                'type' => 'string',
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    #[Test]
    public function it_returns_error_for_nonexistent_response()
    {
        SwaggerServer::tool(GetResponseTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'v1',
            'response' => 'NonExistentResponse',
        ])->assertHasErrors(['Response not found']);
    }

    #[Test]
    public function it_returns_error_for_nonexistent_file()
    {
        SwaggerServer::tool(GetResponseTool::class, [
            'filename' => 'invalid-filename',
            'version' => 'v1',
            'response' => 'NotFound',
        ])->assertHasErrors(['Swagger file not found']);
    }

    #[Test]
    public function it_returns_error_for_invalid_version()
    {
        SwaggerServer::tool(GetResponseTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'invalid-version',
            'response' => 'NotFound',
        ])->assertHasErrors(['Swagger file not found']);
    }
}
