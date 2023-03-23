<?php

namespace NextApps\SwaggerUi\Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;
use NextApps\SwaggerUi\SwaggerUiServiceProvider;

class OpenApiRouteTest extends TestCase
{
    protected function setUp() : void
    {
        parent::setUp();

        config()->set('swagger-ui.files.0.versions', ['v1' => __DIR__ . '/testfiles/openapi.json']);

        Gate::define('viewSwaggerUI', fn (?Authenticatable $user) => true);
    }

    protected function getPackageProviders($app) : array
    {
        return [SwaggerUiServiceProvider::class];
    }

    public function openApiFileProvider() : array
    {
        return [
            'json file' => [__DIR__ . '/testfiles/openapi.json'],
            'yaml file' => [__DIR__ . '/testfiles/openapi.yaml'],
        ];
    }

    /**
     * @test
     *
     * @dataProvider openApiFileProvider
     */
    public function it_sets_server_to_current_app_url_if_modify_file_is_enabled($openApiFile)
    {
        config()->set('swagger-ui.files.0.versions', ['v1' => $openApiFile]);
        config()->set('swagger-ui.files.0.modify_file', true);
        config()->set('app.url', 'http://foo.bar');

        $this->get('swagger/v1')
            ->assertStatus(200)
            ->assertJsonCount(1, 'servers')
            ->assertJsonPath('servers.0.url', 'http://foo.bar');
    }

    /**
     * @test
     *
     * @dataProvider openApiFileProvider
     */
    public function it_sets_oauth_urls_by_combining_configured_paths_with_current_app_url_if_modify_file_is_enabled($openApiFile)
    {
        config()->set('swagger-ui.files.0.versions', ['v1' => $openApiFile]);
        config()->set('swagger-ui.files.0.modify_file', true);
        config()->set('swagger-ui.files.0.oauth', ['token_path' => 'this-is-token-path', 'refresh_path' => 'this-is-refresh-path', 'authorization_path' => 'this-is-authorization-path']);

        $this->get('swagger/v1')
            ->assertStatus(200)
            ->assertJsonPath('components.securitySchemes.Foobar.flows.password.tokenUrl', 'http://localhost/this-is-token-path')
            ->assertJsonPath('components.securitySchemes.Foobar.flows.password.refreshUrl', 'http://localhost/this-is-refresh-path')
            ->assertJsonPath('components.securitySchemes.Foobar.flows.authorizationCode.authorizationUrl', 'http://localhost/this-is-authorization-path')
            ->assertJsonPath('components.securitySchemes.Foobar.flows.authorizationCode.tokenUrl', 'http://localhost/this-is-token-path')
            ->assertJsonPath('components.securitySchemes.Foobar.flows.authorizationCode.refreshUrl', 'http://localhost/this-is-refresh-path');
    }

    /**
     * @test
     *
     * @dataProvider openApiFileProvider
     */
    public function it_doesnt_sets_server_to_current_app_url_if_modify_file_is_disabled($openApiFile)
    {
        config()->set('swagger-ui.files.0.versions', ['v1' => $openApiFile]);
        config()->set('swagger-ui.files.0.modify_file', false);

        config()->set('app.url', 'http://foo.bar');

        $this->get('swagger/v1')
            ->assertStatus(200)
            ->assertJsonCount(1, 'servers')
            ->assertJsonPath('servers.0.url', 'http://localhost:3000');
    }

    /**
     * @test
     *
     * @dataProvider openApiFileProvider
     */
    public function it_doesnt_sets_oauth_urls_by_combining_configured_paths_with_current_app_url_if_modify_file_is_disabled($openApiFile)
    {
        config()->set('swagger-ui.files.0.versions', ['v1' => $openApiFile]);
        config()->set('swagger-ui.files.0.modify_file', false);
        config()->set('swagger-ui.files.0.oauth', ['token_path' => 'this-is-token-path', 'refresh_path' => 'this-is-refresh-path', 'authorization_path' => 'this-is-authorization-path']);

        $this->get('swagger/v1')
            ->assertStatus(200)
            ->assertJsonPath('components.securitySchemes.Foobar.flows.password.tokenUrl', 'http://localhost:3000/password/tokenUrl')
            ->assertJsonPath('components.securitySchemes.Foobar.flows.password.refreshUrl', 'http://localhost:3000/password/refreshUrl')
            ->assertJsonPath('components.securitySchemes.Foobar.flows.authorizationCode.authorizationUrl', 'http://localhost:3000/authorizationCode/authorizationUrl')
            ->assertJsonPath('components.securitySchemes.Foobar.flows.authorizationCode.tokenUrl', 'http://localhost:3000/authorizationCode/tokenUrl')
            ->assertJsonPath('components.securitySchemes.Foobar.flows.authorizationCode.refreshUrl', 'http://localhost:3000/authorizationCode/refreshUrl');
    }
}
