# Laravel API key

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jargoud/laravel-api-key.svg?style=flat-square)](https://packagist.org/packages/jargoud/laravel-api-key)
[![Build Status](https://img.shields.io/travis/jargoud/laravel-api-key/master.svg?style=flat-square)](https://travis-ci.org/jargoud/laravel-api-key)
[![Quality Score](https://img.shields.io/scrutinizer/g/jargoud/laravel-api-key.svg?style=flat-square)](https://scrutinizer-ci.com/g/jargoud/laravel-api-key)
[![Total Downloads](https://img.shields.io/packagist/dt/jargoud/laravel-api-key.svg?style=flat-square)](https://packagist.org/packages/jargoud/laravel-api-key)

This package provides a simple API key authentication, based
on [Laravel Authentication](https://laravel.com/docs/master/authentication) system.

## Summary

[[_TOC_]]

## Installation

You can install the package via composer:

```bash
composer config repositories.laravel-api-key vcs https://github.com/jargoud/laravel-api-key.git
composer require jargoud/laravel-api-key
```

The package will automatically register itself.

You can publish the package's assets with one of the following commands:

```shell
php artisan vendor:publish --provider="Jargoud\LaravelApiKey\Providers\LaravelApiKeyServiceProvider" --tag="config"
php artisan vendor:publish --provider="Jargoud\LaravelApiKey\Providers\LaravelApiKeyServiceProvider" --tag="views"
```

## Usage

The package create an [ApiKey](./src/Models/ApiKey.php) entity, which contains:
- a name,
- a value: it's the Api key value, it's hashed so you should copy it before saving the model!
- a restriction selector: `none`, by `referer` or by `ip`,
- a list of referers to allow client requests,
- a list of ip addresses to allow server requests.

### Configuration

Add the package's guard and provider in your `auth.php` config file:

```php
// config/auth.php
return [
    // ...
    
    'guards' => [
        // ...

        'api' => [
            'driver' => 'api_token',
            'provider' => 'api_keys',
            'storage_key' => Jargoud\LaravelApiKey\Models\ApiKey::COLUMN_VALUE,
            'hash' => true,
        ],
    ],
    
    // ...
    
    'providers' => [
        // ...

        'api_keys' => [
            'driver' => 'eloquent',
            'model' => Jargoud\LaravelApiKey\Models\ApiKey::class,
        ],
    ],
    
    // ...
];
```

In order to protect your Api routes, you should use the `auth` middleware, as follow:

```php
Route::middleware('auth:api')->group(
    function() {
        // ...
    }
);
```

In order to query your Api, you should pass the Api key inside your requests. It can be a query parameter, an input or a
HTTP header. See [ApiTokenGuard.php](./src/Auth/ApiTokenGuard.php#L116) to find out how it's retrieved by the guard.

### Backpack

If your app uses [Laravel Backpack](https://backpackforlaravel.com/), it provides a CRUD controller that you can use in
order to manage your Api keys.

To enable it, set `api-key.backpack.enabled` configuration value to `true`.

It will auto register the route and you can then add a tab in your sidebar:

```html

<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('api-key') }}">
        <i class="nav-icon la la-key"></i>
        Api keys
    </a>
</li>
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

If you discover any security related issues, please email jeremy.argoud@gmail.com instead of using the issue tracker.

## Credits

- [Jérémy Argoud](https://github.com/jargoud)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
