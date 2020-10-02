# Laravel Swagger UI

[![Latest Version on Packagist](https://img.shields.io/packagist/v/nextapps/laravel-swagger-ui.svg?style=flat-square)](https://packagist.org/packages/nextapps/laravel-swagger-ui)
[![Build Status](https://img.shields.io/travis/nextapps/laravel-swagger-ui/master.svg?style=flat-square)](https://travis-ci.org/nextapps/laravel-swagger-ui)
[![Quality Score](https://img.shields.io/scrutinizer/g/nextapps/laravel-swagger-ui.svg?style=flat-square)](https://scrutinizer-ci.com/g/nextapps/laravel-swagger-ui)
[![Total Downloads](https://img.shields.io/packagist/dt/nextapps/laravel-swagger-ui.svg?style=flat-square)](https://packagist.org/packages/nextapps/laravel-swagger-ui)

This package makes it easy to make your project's Swagger (OpenAPI v3 JSON) file accessible in a Swagger UI right in your Laravel application.

The Swagger UI will automatically use your current project environment. It will set your api's base url to the active base url. Possible oauth2 configuration, such as urls and client-id/client-secret, can also be injected in Swagger UI via the configuration file.

## Installation

You can install the package via composer:

```bash
composer require nextapps/laravel-swagger-ui
```

After installing Laravel Swagger UI, publish its service provider and configuration file using the `swagger-ui:install` Artisan command.

```bash
php artisan swagger-ui:install
```

## Usage

``` php
// Usage description here
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email gunther@nextapps.be instead of using the issue tracker.

## Credits

- [Günther Debrauwer](https://github.com/gdebrauwer)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
