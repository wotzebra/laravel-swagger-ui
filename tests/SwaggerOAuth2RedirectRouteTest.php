<?php

namespace NextApps\SwaggerUi\Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use NextApps\SwaggerUi\SwaggerUiServiceProvider;

class SwaggerOAuth2RedirectRouteTest extends TestCase
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
    public function it_returns_swagger_ui_oauth2_redirect_html_file_content()
    {
        Http::fake([
            'https://unpkg.com/swagger-ui-dist@latest/oauth2-redirect.html' => Http::response('foo'),
        ]);

        $this->get('swagger/oauth2-redirect')
            ->assertStatus(200)
            ->assertSeeText('foo');

        Http::assertSentCount(1);
    }
}
