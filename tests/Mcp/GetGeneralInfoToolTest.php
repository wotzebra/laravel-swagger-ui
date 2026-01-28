<?php

namespace Wotz\SwaggerUi\Tests\Mcp;

use Laravel\Mcp\Server\McpServiceProvider;
use Wotz\SwaggerUi\Mcp\Servers\SwaggerServer;
use Wotz\SwaggerUi\Mcp\Tools\GetGeneralInfoTool;
use Wotz\SwaggerUi\Tests\TestCase;

class GetGeneralInfoToolTest extends TestCase
{
    protected function getPackageProviders($app) : array
    {
        return [McpServiceProvider::class];
    }

    /** @test */
    public function it_retrieves_general_info_for_a_valid_swagger_file()
    {
        config()->set('swagger-ui.files.1.modify_file', true);
        config()->set('swagger-ui.files.1.server_url', 'http://foo.bar/api');
        config()->set('swagger-ui.files.1.oauth', ['token_path' => 'this-is-token-path', 'refresh_path' => 'this-is-refresh-path', 'authorization_path' => 'this-is-authorization-path']);

        SwaggerServer::tool(GetGeneralInfoTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'v1',
        ])->assertStructuredContent([
            'info' => [
                'title' => 'openapi',
                'version' => '1.0',
            ],
            'servers' => [
                [
                    'url' => 'http://foo.bar/api',
                ],
            ],
            'components' => [
                'securitySchemes' => [
                    'Foobar' => [
                        'type' => 'oauth2',
                        'flows' => [
                            'password' => [
                                'tokenUrl' => 'http://localhost/this-is-token-path',
                                'scopes' => [],
                                'refreshUrl' => 'http://localhost/this-is-refresh-path',
                            ],
                            'authorizationCode' => [
                                'authorizationUrl' => 'http://localhost/this-is-authorization-path',
                                'tokenUrl' => 'http://localhost/this-is-token-path',
                                'refreshUrl' => 'http://localhost/this-is-refresh-path',
                                'scopes' => [],
                            ],
                        ],
                        'description' => '',
                    ],
                ],
            ],
            'security' => [
                [
                    'FooBar' => [],
                ],
            ],
        ]);
    }

    /** @test */
    public function it_returns_error_for_nonexistent_file()
    {
        SwaggerServer::tool(GetGeneralInfoTool::class, [
            'filename' => 'invalid-filename',
            'version' => 'v1',
        ])->assertHasErrors(['Swagger file not found']);
    }

    /** @test */
    public function it_returns_error_for_invalid_version()
    {
        SwaggerServer::tool(GetGeneralInfoTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'invalid-version',
        ])->assertHasErrors(['Swagger file not found']);
    }
}
