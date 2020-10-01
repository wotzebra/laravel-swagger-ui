<?php

namespace NextApps\SwaggerUI\Tests;

use Orchestra\Testbench\TestCase;
use NextApps\SwaggerUI\SwaggerUIServiceProvider;

class ExampleTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [SwaggerUIServiceProvider::class];
    }

    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
