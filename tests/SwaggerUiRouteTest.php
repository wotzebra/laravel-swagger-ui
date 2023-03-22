<?php

namespace NextApps\SwaggerUi\Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;
use NextApps\SwaggerUi\SwaggerUiServiceProvider;

class SwaggerUiRouteTest extends TestCase
{
    protected function setUp() : void
    {
        parent::setUp();

        config()->set('swagger-ui.files.0.versions', ['v1' => __DIR__ . '/testfiles/openapi.json']);
        config()->set('swagger-ui.files.0.oauth', ['client_id' => 1, 'client_secret' => 'foobar']);

        Gate::define('viewSwaggerUI', fn (?Authenticatable $user) => true);
    }

    protected function getPackageProviders($app) : array
    {
        return [SwaggerUiServiceProvider::class];
    }

    /** @test */
    public function it_provides_openapi_route_as_url()
    {
        $this->get('swagger')
            ->assertStatus(200)
            ->assertSee('url: \'' . 'swagger/v1' . '\'', false);
    }

    /** @test */
    public function it_fills_oauth_client_id_and_secret_from_config()
    {
        $this->get('swagger')
            ->assertStatus(200)
            ->assertSee('clientId: \'1\',', false)
            ->assertSee('clientSecret: \'foobar\',', false);
    }

    /** @test */
    public function it_allows_multiple_routes()
    {
        $this->get('swagger2')
            ->assertStatus(200)
            ->assertSee('url: \'' . 'swagger2/v1' . '\'', false);
    }

    /** @test */
    public function it_supports_multiple_verions()
    {
        $this->get('swagger-with-versions')
            ->assertStatus(200)
            ->assertSee('url: \'' . 'swagger-with-versions/v1' . '\'', false)
            ->assertSee('url: \'' . 'swagger-with-versions/v2' . '\'', false);
    }
}
