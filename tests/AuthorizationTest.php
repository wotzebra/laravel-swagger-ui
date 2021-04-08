<?php

namespace jamesRUS52\Laravel\SwaggerUi\Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Gate;
use jamesRUS52\Laravel\SwaggerUi\SwaggerUiServiceProvider;
use Orchestra\Testbench\TestCase;

class AuthorizationTest extends TestCase
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
    }

    /**
     * Get the package providers.
     *
     * @param mixed $app
     *
     * @return string[]
     */
    protected function getPackageProviders($app)
    {
        return [SwaggerUiServiceProvider::class];
    }

    /** @test */
    public function it_denies_access_in_default_installation()
    {
        $this->get('swagger')->assertStatus(403);
        $this->get('swagger/openapi.json')->assertStatus(403);
    }

    /** @test */
    public function it_denies_access_in_default_installation_for_any_auth_user()
    {
        $this->actingAs(new Authenticated());

        $this->get('swagger')->assertStatus(403);
        $this->get('swagger/openapi.json')->assertStatus(403);
    }

    /** @test */
    public function it_denies_access_for_guests()
    {
        Gate::define('viewSwaggerUI', fn () => true);

        $this->get('swagger')->assertStatus(403);
        $this->get('swagger/openapi.json')->assertStatus(403);
    }

    /** @test */
    public function it_allows_access_to_user_if_allowed_by_gate()
    {
        $this->actingAs(new Authenticated());

        Gate::define('viewSwaggerUI', function (Authenticated $user) {
            return $user->getAuthIdentifier() === 'swagger-ui-test';
        });

        $this->get('swagger')->assertStatus(200);
        $this->get('swagger/openapi.json')->assertStatus(200);
    }

    /** @test */
    public function it_denies_access_to_user_if_not_allowed_by_gate()
    {
        $this->actingAs(new Authenticated());

        Gate::define('viewSwaggerUI', fn () => false);

        $this->get('swagger')->assertStatus(403);
        $this->get('swagger/openapi.json')->assertStatus(403);
    }

    /** @test */
    public function it_allows_access_to_guest_if_allowed_by_gate()
    {
        Gate::define('viewSwaggerUI', fn (?Authenticated $user) => true);

        $this->get('swagger')->assertStatus(200);
        $this->get('swagger/openapi.json')->assertStatus(200);
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
