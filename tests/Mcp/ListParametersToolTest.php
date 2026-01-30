<?php

namespace Wotz\SwaggerUi\Tests\Mcp;

use Laravel\Mcp\Server\McpServiceProvider;
use PHPUnit\Framework\Attributes\Test;
use Wotz\SwaggerUi\Mcp\Servers\SwaggerServer;
use Wotz\SwaggerUi\Mcp\Tools\ListParametersTool;
use Wotz\SwaggerUi\Tests\TestCase;

class ListParametersToolTest extends TestCase
{
    protected function getPackageProviders($app) : array
    {
        return [McpServiceProvider::class];
    }

    #[Test]
    public function it_lists_all_reusable_parameters_for_a_valid_swagger_file()
    {
        SwaggerServer::tool(ListParametersTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'v1',
        ])->assertStructuredContent([
            'X-API-Version' => [
                'name' => 'X-API-Version',
                'in' => 'header',
                'required' => true,
                'schema' => [
                    'type' => 'string',
                ],
                'description' => 'Current API version.',
            ],
            'Accept-Language' => [
                'name' => 'Accept-Language',
                'in' => 'header',
                'schema' => [
                    'type' => 'string',
                ],
                'description' => 'Used to determine the user\'s language and to return content in that language.',
            ],
            'X-Timezone' => [
                'name' => 'X-Timezone',
                'in' => 'header',
                'required' => false,
                'schema' => [
                    'type' => 'string',
                ],
                'description' => 'User\'s timezone name',
            ],
        ]);
    }

    #[Test]
    public function it_returns_error_for_nonexistent_file()
    {
        SwaggerServer::tool(ListParametersTool::class, [
            'filename' => 'invalid-filename',
            'version' => 'v1',
        ])->assertHasErrors(['Swagger file not found']);
    }

    #[Test]
    public function it_returns_error_for_invalid_version()
    {
        SwaggerServer::tool(ListParametersTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'invalid-version',
        ])->assertHasErrors(['Swagger file not found']);
    }
}
