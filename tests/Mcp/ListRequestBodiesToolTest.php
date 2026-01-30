<?php

namespace Wotz\SwaggerUi\Tests\Mcp;

use Laravel\Mcp\Server\McpServiceProvider;
use PHPUnit\Framework\Attributes\Test;
use Wotz\SwaggerUi\Mcp\Servers\SwaggerServer;
use Wotz\SwaggerUi\Mcp\Tools\ListRequestBodiesTool;
use Wotz\SwaggerUi\Tests\TestCase;

class ListRequestBodiesToolTest extends TestCase
{
    protected function getPackageProviders($app) : array
    {
        return [McpServiceProvider::class];
    }

    #[Test]
    public function it_lists_all_request_bodies_for_a_valid_swagger_file()
    {
        SwaggerServer::tool(ListRequestBodiesTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'v1',
        ])->assertStructuredContent([
            'requestBodies' => [
                'UserCreate',
                'BookingCreate',
            ],
        ]);
    }

    #[Test]
    public function it_returns_error_for_nonexistent_file()
    {
        SwaggerServer::tool(ListRequestBodiesTool::class, [
            'filename' => 'invalid-filename',
            'version' => 'v1',
        ])->assertHasErrors(['Swagger file not found']);
    }

    #[Test]
    public function it_returns_error_for_invalid_version()
    {
        SwaggerServer::tool(ListRequestBodiesTool::class, [
            'filename' => 'swagger-with-versions',
            'version' => 'invalid-version',
        ])->assertHasErrors(['Swagger file not found']);
    }
}
