<?php

namespace Wotz\SwaggerUi\Tests\Mcp;

use Laravel\Mcp\Server\McpServiceProvider;
use Wotz\SwaggerUi\Mcp\Servers\SwaggerServer;
use Wotz\SwaggerUi\Mcp\Tools\ListSwaggerFilesTool;
use Wotz\SwaggerUi\Tests\TestCase;

class ListSwaggerFilesToolTest extends TestCase
{
    protected function getPackageProviders($app) : array
    {
        return [McpServiceProvider::class];
    }

    /** @test */
    public function it_lists_all_swagger_files_with_their_versions()
    {
        SwaggerServer::tool(ListSwaggerFilesTool::class)
            ->assertStructuredContent([
                [
                    'name' => 'swagger-with-versions',
                    'versions' => ['v1', 'v2'],
                ],
                [
                    'name' => 'path/with/multiple/segments/swagger-with-versions',
                    'versions' => ['v1', 'v2'],
                ],
            ]);
    }
}
