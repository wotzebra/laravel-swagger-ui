<?php

namespace NextApps\SwaggerUi\Test;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;
use NextApps\SwaggerUi\SwaggerUiServiceProvider;
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

    public function provideOpenApiFiles()
    {
        return [
            'json file' => [__DIR__.'/testfiles/openapi.json'],
            'yaml file' => [__DIR__.'/testfiles/openapi.yaml'],
        ];
    }

    /**
     * @test
     * @dataProvider provideOpenApiFiles
     */
    public function it_sets_server_to_current_app_url($openApiFile)
    {
        config()->set('swagger-ui.file', $openApiFile);
        config()->set('app.url', 'http://foo.bar');

        $this->get('swagger/openapi.json')
            ->assertStatus(200)
            ->assertJsonCount(1, 'servers')
            ->assertJsonPath('servers.0.url', 'http://foo.bar');
    }

    /** @test */
    public function it_sets_oauth_urls_by_combining_configured_paths_with_current_app_url()
    {
        config()->set('swagger-ui.oauth.token_path', 'this-is-token-path');
        config()->set('swagger-ui.oauth.refresh_path', 'this-is-refresh-path');
        config()->set('swagger-ui.oauth.authorization_path', 'this-is-authorization-path');

        $this->get('swagger/openapi.json')
            ->assertStatus(200)
            ->assertJsonPath('components.securitySchemes.Foobar.flows.password.tokenUrl', 'http://localhost/this-is-token-path')
            ->assertJsonPath('components.securitySchemes.Foobar.flows.password.refreshUrl', 'http://localhost/this-is-refresh-path')
            ->assertJsonPath('components.securitySchemes.Foobar.flows.authorizationCode.authorizationUrl', 'http://localhost/this-is-authorization-path')
            ->assertJsonPath('components.securitySchemes.Foobar.flows.authorizationCode.tokenUrl', 'http://localhost/this-is-token-path')
            ->assertJsonPath('components.securitySchemes.Foobar.flows.authorizationCode.refreshUrl', 'http://localhost/this-is-refresh-path');
    }
}
