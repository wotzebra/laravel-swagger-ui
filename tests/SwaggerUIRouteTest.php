<?php

namespace NextApps\SwaggerUI\Test;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;
use NextApps\SwaggerUI\SwaggerUIServiceProvider;
use Orchestra\Testbench\TestCase;

class SwaggerUIRouteTest extends TestCase
{
    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('swagger-ui.file', __DIR__.'/testfiles/openapi.json');

        Gate::define('viewSwaggerUI', fn (?Authenticatable $user) => true);
    }

    /**
     * Get the package providers.
     *
     * @param mixed $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [SwaggerUIServiceProvider::class];
    }

    /** @test */
    public function it_provides_openapi_route_as_url()
    {
        config()->set('swagger-ui.oauth.client_id', 1);
        config()->set('swagger-ui.oauth.client_secret', 'foobar');

        $this->get('swagger')
            ->assertStatus(200)
            ->assertSee('url: \''.route('swagger-openapi-json', [], false).'\'', false);
    }

    /** @test */
    public function it_fills_oauth_client_id_and_secret_from_config()
    {
        config()->set('swagger-ui.oauth.client_id', 1);
        config()->set('swagger-ui.oauth.client_secret', 'foobar');

        $this->get('swagger')
            ->assertStatus(200)
            ->assertSee('clientId: \'1\',', false)
            ->assertSee('clientSecret: \'foobar\',', false);
    }
}
