<?php

namespace NextApps\SwaggerUi\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function defineEnvironment($app)
    {
        $app['config']->set('swagger-ui.files.1', config('swagger-ui.files.0'));
        $app['config']->set('swagger-ui.files.1.oauth', ['client_id' => 1, 'client_secret' => 'foobar']);
        $app['config']->set('swagger-ui.files.1.path', 'swagger2');

        $app['config']->set('swagger-ui.files.2', config('swagger-ui.files.0'));
        $app['config']->set('swagger-ui.files.2.versions', [
            'v1' => __DIR__ . '/testfiles/openapi.json',
            'v2' => __DIR__ . '/testfiles/openapi-v2.json'
        ]);
        $app['config']->set('swagger-ui.files.2.path', 'swagger-with-versions');
    }
}
