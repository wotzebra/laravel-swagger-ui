# Laravel Swagger UI

[![Latest Version on Packagist](https://img.shields.io/packagist/v/james.rus52/laravel-swagger-ui.svg?style=flat-square)](https://packagist.org/packages/james.rus52/laravel-swagger-ui)
[![GitHub 'Run Tests' Workflow Status](https://img.shields.io/github/workflow/status/james.rus52/laravel-swagger-ui/run-tests?label=tests&style=flat-square&logo=github)](https://github.com/jamesRUS52/laravel-swagger-ui/actions?query=workflow%3Arun-tests)
[![Total Downloads](https://img.shields.io/packagist/dt/james.rus52/laravel-swagger-ui.svg?style=flat-square)](https://packagist.org/packages/james.rus52/laravel-swagger-ui)

This package makes it easy to make your project's Swagger (OpenAPI v3 JSON) file accessible in a Swagger UI right in your Laravel application.

The Swagger UI will automatically use your current project environment. It will set your api's base url to the active base url. Possible oauth2 configuration, such as urls and client-id/client-secret, can also be injected in Swagger UI via the configuration file.

## Installation

You can install the package via composer:

```bash
composer require james.rus52/laravel-swagger-ui
```

After installing Laravel Swagger UI, publish its service provider and configuration file using the `swagger-ui:install` Artisan command.

```bash
php artisan swagger-ui:install
```

## Usage

The Swagger UI is exposed at `/swagger`. By default, you will only be able to access it in the local environment. Within your `app/Providers/SwaggerUiServiceProvider.php` file, there is a `gate` method. This authorization gate controls access to Swagger UI in non-local environments. You can modify this gate as needed to restrict access to your Swagger UI and Swagger (OpenAPI v3) file:

``` php
/**
 * Register the Swagger UI gate.
 *
 * This gate determines who can access Swagger UI in non-local environments.
 *
 * @return void
 */
protected function gate()
{
    Gate::define('viewSwaggerUI', function ($user = null) {
        return in_array(optional($user)->email, [
            //
        ]);
    });
}
```

In the published `config/swagger-ui.php` file, you edit the path to the Swagger UI and the location of the Swagger (OpenAPI v3) file. By default, the package expects to find the OpenAPI json file in 'resources/swagger' directory.

```php
// in config/swagger-ui.php

return [
    // ...

    'path' => 'swagger',

    'file' => resource_path('swagger/openapi.json'),

    // ...
];
```

You also have the option to customize the oauth setup. By default, the oauth paths are configured based on Laravel Passport.
You can also set a client ID and client secret. These values will be automatically prefilled in the authentication view in Swagger UI.

```php
// in config/swagger-ui.php

return [
    // ...

    'oauth' => [
        'token_path' => 'oauth/token',
        'refresh_path' => 'oauth/token',
        'authorization_path' => 'oauth/authorize',

        'client_id' => env('SWAGGER_UI_OAUTH_CLIENT_ID'),
        'client_secret' => env('SWAGGER_UI_OAUTH_CLIENT_SECRET'),
    ];

    // ...
];
```

### Testing

``` bash
composer test
```

## Linting

```bash
composer lint
```


### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email gunther@nextapps.be instead of using the issue tracker.

## Credits

- [Anton V. Davydov](https://github.com/jamesRUS52)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
