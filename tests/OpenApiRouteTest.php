<?php

namespace jamesRUS52\Laravel\SwaggerUi\Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;
use jamesRUS52\Laravel\SwaggerUi\SwaggerUiServiceProvider;
use Orchestra\Testbench\TestCase;

class OpenApiRouteTest extends TestCase
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
        return [SwaggerUiServiceProvider::class];
    }

    /** @test */
    public function it_sets_servers()
    {
        config()->set('swagger-ui.servers', ['http://foo.bar', 'http://bar.baz']);

        $this->get('swagger/openapi.json')
            ->assertStatus(200)
            ->assertJsonCount(2, 'servers')
            ->assertJsonPath('servers.0.url', 'http://foo.bar')
            ->assertJsonPath('servers.1.url', 'http://bar.baz');
    }

    /** @test */
    public function it_does_not_set_servers_if_config_variable_is_null()
    {
        config()->set('swagger-ui.servers', null);

        $this->get('swagger/openapi.json')
            ->assertStatus(200)
            ->assertJsonCount(1, 'servers')
            ->assertJsonPath('servers.0.url', 'http://localhost:3000');
    }

    /** @test */
    public function it_sets_oauth_urls()
    {
        config()->set('swagger-ui.oauth.token_url', 'http://foo.bar/this-is-token-path');
        config()->set('swagger-ui.oauth.refresh_url', 'http://bar.baz/this-is-refresh-path');
        config()->set('swagger-ui.oauth.authorization_url', 'http://bar.foo/this-is-authorization-path');

        $this->get('swagger/openapi.json')
            ->assertStatus(200)
            ->assertJsonPath('components.securitySchemes.Foobar.flows.password.tokenUrl', 'http://foo.bar/this-is-token-path')
            ->assertJsonPath('components.securitySchemes.Foobar.flows.password.refreshUrl', 'http://bar.baz/this-is-refresh-path')
            ->assertJsonPath('components.securitySchemes.Foobar.flows.authorizationCode.authorizationUrl', 'http://bar.foo/this-is-authorization-path')
            ->assertJsonPath('components.securitySchemes.Foobar.flows.authorizationCode.tokenUrl', 'http://foo.bar/this-is-token-path')
            ->assertJsonPath('components.securitySchemes.Foobar.flows.authorizationCode.refreshUrl', 'http://bar.baz/this-is-refresh-path');
    }
}
