<?php

namespace NextApps\SwaggerUi\Tests;

use JMac\Testing\Traits\AdditionalAssertions;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use AdditionalAssertions;

    protected function defineEnvironment($app)
    {
        $app['config']->set('swagger-ui.files.1', config('swagger-ui.files.0'));
        $app['config']->set('swagger-ui.files.1.versions', [
            'v1' => __DIR__ . '/testfiles/openapi.json',
            'v2' => __DIR__ . '/testfiles/openapi-v2.json',
        ]);
        $app['config']->set('swagger-ui.files.1.path', 'swagger-with-versions');
        $app['config']->set('swagger-ui.files.1.middleware', ['web']);
    }
}
