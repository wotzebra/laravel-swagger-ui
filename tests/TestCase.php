<?php

namespace Wotz\SwaggerUi\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use Laravel\Mcp\Server\Testing\TestResponse;
use Orchestra\Testbench\TestCase as BaseTestCase;
use PHPUnit\Framework\Assert;

abstract class TestCase extends BaseTestCase
{
    use AdditionalAssertions,
        WithFaker;

    public function setUp() : void
    {
        parent::setUp();

        TestResponse::macro('assertStructuredContent', function (array $expected) {
            Assert::assertSame($expected, $this->response->toArray()['result']['structuredContent']);
        });
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('app.url', 'http://foo.bar');
        $app['config']->set('swagger-ui.files.1', config('swagger-ui.files.0'));
        $app['config']->set('swagger-ui.files.1.versions', [
            'v1' => __DIR__ . '/testfiles/openapi.json',
            'v2' => __DIR__ . '/testfiles/openapi-v2.json',
        ]);
        $app['config']->set('swagger-ui.files.1.path', 'swagger-with-versions');
        $app['config']->set('swagger-ui.files.1.middleware', ['web']);

        $app['config']->set('swagger-ui.files.2', config('swagger-ui.files.0'));
        $app['config']->set('swagger-ui.files.2.versions', [
            'v1' => __DIR__ . '/testfiles/openapi.json',
            'v2' => __DIR__ . '/testfiles/openapi-v2.json',
        ]);
        $app['config']->set('swagger-ui.files.2.path', 'path/with/multiple/segments/swagger-with-versions');
        $app['config']->set('swagger-ui.files.2.middleware', ['web']);

        $app['config']->set('swagger-ui.mcp.enabled', true);
        $app['config']->set('swagger-ui.mcp.middleware', []);
    }
}
