<?php

namespace NextApps\SwaggerUi\Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;
use NextApps\SwaggerUi\SwaggerUiServiceProvider;

class AuthorizationTest extends TestCase
{
    protected function setUp() : void
    {
        parent::setUp();

        config()->set('swagger-ui.files.0.versions', ['v1' => __DIR__ . '/testfiles/openapi.json']);
    }

    protected function getPackageProviders($app) : array
    {
        return [SwaggerUiServiceProvider::class];
    }

    /** @test */
    public function it_denies_access_in_default_installation()
    {
        $this->get('swagger')->assertStatus(403);
        $this->get('swagger/v1')->assertStatus(403);
    }

    /** @test */
    public function it_denies_access_in_default_installation_for_any_auth_user()
    {
        $this->actingAs(new Authenticated());

        $this->get('swagger')->assertStatus(403);
        $this->get('swagger/v1')->assertStatus(403);
    }

    /** @test */
    public function it_denies_access_for_guests()
    {
        Gate::define('viewSwaggerUI', fn () => true);

        $this->get('swagger')->assertStatus(403);
        $this->get('swagger/v1')->assertStatus(403);
    }

    /** @test */
    public function it_allows_access_to_user_if_allowed_by_gate()
    {
        $this->actingAs(new Authenticated());

        Gate::define('viewSwaggerUI', function (Authenticated $user) {
            return $user->getAuthIdentifier() === 'swagger-ui-test';
        });

        $this->get('swagger')->assertStatus(200);
        $this->get('swagger/v1')->assertStatus(200);
    }

    /** @test */
    public function it_denies_access_to_user_if_not_allowed_by_gate()
    {
        $this->actingAs(new Authenticated());

        Gate::define('viewSwaggerUI', fn () => false);

        $this->get('swagger')->assertStatus(403);
        $this->get('swagger/v1')->assertStatus(403);
    }

    /** @test */
    public function it_allows_access_to_guest_if_allowed_by_gate()
    {
        Gate::define('viewSwaggerUI', fn (?Authenticated $user) => true);

        $this->get('swagger')->assertStatus(200);
        $this->get('swagger/v1')->assertStatus(200);
    }
}

class Authenticated implements Authenticatable
{
    public $email;

    public function getAuthIdentifierName()
    {
        return 'Swagger UI Test';
    }

    public function getAuthIdentifier()
    {
        return 'swagger-ui-test';
    }

    public function getAuthPassword()
    {
        return 'secret';
    }

    public function getRememberToken()
    {
        return 'i-am-swagger-ui';
    }

    public function setRememberToken($value)
    {
        //
    }

    public function getRememberTokenName()
    {
        //
    }
}
