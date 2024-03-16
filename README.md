# Typescriptable for Laravel

![Banner with printer shop picture in background and Typescriptable Laravel title](https://raw.githubusercontent.com/kiwilan/typescriptable-laravel/main/docs/banner.jpg)

[![php][php-version-src]][php-version-href]
[![version][version-src]][version-href]
[![downloads][downloads-src]][downloads-href]
[![license][license-src]][license-href]
[![codecov][codecov-src]][codecov-href]
[![tests][tests-src]][tests-href]

[![laravel][laravel-src]][laravel-href]
[![npm][npm-version-src]][npm-version-href]

PHP package for Laravel **to type Eloquent models**, **routes**, [**Spatie Settings**](https://github.com/spatie/laravel-settings) with **autogenerated TypeScript**.

If you want to use some helpers with [Inertia](https://inertiajs.com/), you can install [associated NPM package](https://www.npmjs.com/package/@kiwilan/typescriptable-laravel).

> [!NOTE]
>
> -   [`kiwilan/typescriptable-laravel`](https://packagist.org/packages/kiwilan/typescriptable-laravel): current PHP package for [Laravel](https://laravel.com/).
> -   [`@kiwilan/typescriptable-laravel`](https://www.npmjs.com/package/@kiwilan/typescriptable-laravel): optional NPM package to use with [Vite](https://vitejs.dev/) and [Inertia](https://inertiajs.com/) to have some helpers, if you want to know more about, [check documentation](https://github.com/kiwilan/typescriptable-laravel/blob/main/lib/README.md).
> -   [`ziggy`](https://github.com/tighten/ziggy) is **NOT REQUIRED**

## Features

-   💽 All Laravel databases are supported: MySQL, PostgreSQL, SQLite, SQL Server
-   💬 Generate TS types for [Eloquent models](https://laravel.com/docs/10.x/eloquent)
-   👭 Generate TS types for [Eloquent relations](https://laravel.com/docs/10.x/eloquent-relationships)
-   🪄 Generate TS types for `casts` (include native `enum` support)
-   📅 Generate TS types for `dates`
-   📝 Generate TS types for `appends` with [`accessors`](https://laravel.com/docs/10.x/eloquent-mutators#accessors-and-mutators)
    -   Partial for `Illuminate\Database\Eloquent\Casts\Attribute`
    -   Old way [`get*Attribute` methods](https://laravel.com/docs/8.x/eloquent-mutators#defining-an-accessor) are totally supported
-   #️⃣ Generate TS types for `counts`
-   📖 Can generate pagination TS types for [Laravel pagination](https://laravel.com/docs/10.x/pagination)
-   💾 Can generate simple PHP classes from Eloquent models
-   ⚙️ Generate TS types for [`spatie/laravel-settings`](https://github.com/spatie/laravel-settings)
-   🛣 Generate TS types for [Laravel routes](https://laravel.com/docs/10.x/routing)
    -   Scan route parameters
    -   For Inertia, you can install [`@kiwilan/typescriptable-laravel`](https://www.npmjs.com/package/@kiwilan/typescriptable-laravel) NPM package to use some helpers
-   ✅ Multiple commands to generate types
    -   `php artisan typescriptable` for models, settings and routes (safe even if you don't use all)
    -   `php artisan typescriptable:models` for Eloquent models
    -   `php artisan typescriptable:settings` for `spatie/laravel-settings`
    -   `php artisan typescriptable:routes` for Laravel routes

### Roadmap

-   [ ] Improve `Casts\Attribute` methods
-   [ ] Add parser for [calebporzio/sushi](https://github.com/calebporzio/sushi)
-   [ ] Add parser for [spatie/laravel-permission](https://github.com/spatie/laravel-permission)
-   [ ] Add parser for [mongodb](https://github.com/mongodb/laravel-mongodb)

## Installation

This version requires [PHP](https://www.php.net/) 8.1-8.3 and supports [Laravel](https://laravel.com/) 11.

| Version                                                                          | L9                 | L10                | L11                |
| -------------------------------------------------------------------------------- | ------------------ | ------------------ | ------------------ |
| [1.12.03](https://packagist.org/packages/kiwilan/typescriptable-laravel#1.12.03) | :white_check_mark: | :white_check_mark: | :x:                |
| [2.0.0](https://packagist.org/packages/kiwilan/typescriptable-laravel#2.0.0)     | :x:                | :x:                | :white_check_mark: |

> [!WARNING]
>
> Laravel 11 dropped [Doctrine DBAL](https://laravel.com/docs/11.x/upgrade#doctrine-dbal-removal). For previous Laravel versions, you can use `1.12.03` version.

You can install the package via composer:

With Laravel 11+

```bash
composer require kiwilan/typescriptable-laravel
```

With Laravel 9-10

```
composer require kiwilan/typescriptable-laravel:1.12.03
```

### About TypeScript

If you want to use `.d.ts` files, you need to use TypeScript in your Laravel project, you have to create a `tsconfig.json` file and add `.d.ts` paths in `compilerOptions.types`:

> [!NOTE]
>
> If you change paths into config or with options, adapt paths.

```json
{
    "compilerOptions": {
        "typeRoots": ["./node_modules/@types", "resources/**/*.d.ts"]
    },
    "include": ["resources/**/*.d.ts"]
}
```

## Configuration

You can publish the config file

```bash
php artisan vendor:publish --tag="typescriptable-config"
```

## Usage

```bash
php artisan typescriptable
```

With options:

-   --`M`|`models`: Generate Models types.
-   --`R`|`routes`: Generate Routes types.
-   --`S`|`settings`: Generate Settings types.

### Eloquent Models

Generate `resources/js/types-models.d.ts` file with all models types.

```bash
php artisan typescriptable:models
```

With options:

-   --`M`|`models-path`: Path to models directory.
-   --`O`|`output-path`: Path to output.
-   --`P`|`php-path`: Path to output PHP classes, if null will not print PHP classes.

### Spatie Settings

If you use [`spatie/laravel-settings`](https://github.com/spatie/laravel-settings), you can generate `resources/js/types-settings.d.ts` file with all settings types.

```bash
php artisan typescriptable:settings
```

With options:

-   --`S`|`settings-path`: Path to settings directory.
-   --`O`|`output-path`: Path to output.

### Routes

Generate `resources/js/types-routes.d.ts` file with all routes types and `resources/js/routes.ts` for routes references.

```bash
php artisan typescriptable:routes
```

With options:

-   --`R`|`routes-path`: Path to routes directory.
-   --`O`|`output-path`: Path to output.

## Troubleshooting

### Database prefix

If you have a database prefix, you can add it in `config/typescriptable.php` file with `DB_PREFIX` env variable.

```php
return [
    'database_prefix' => env('DB_PREFIX', ''),
];
```

Or you can use `DB_PREFIX` into `config/database.php` file.

```php
'prefix' => env('DB_PREFIX', ''),
```

Two configs works.

### Override models

`kiwilan/typescriptable-laravel` will cover many cases, but if you want to override some models, you can just create a type like `resources/js/types/index.ts` and extends `Model` type.

```ts
interface BookAdvanced extends App.Models.Book {
    pivot: {
        created_at: string;
        updated_at: string;
    };
}
```

And you can import custom type in your code when you need to use advanced type.

## Examples

Check [examples](docs/examples.md) documentation.

## Testing

Create a `.env` file with your database configuration

```bash
cp .env.example .env
```

And you can run tests

```bash
composer test
```

### Docker database

> [!NOTE]
>
> To install this on M1 Mac, you need to enable `Use Rosetta for x86/amd64 emulation on Apple Silicon` in Docker preferences.

To install MySQL with Docker

```bash
docker run --name mysql \
    -e MYSQL_ROOT_PASSWORD=root \
    -e MYSQL_USER=testing \
    -e MYSQL_PASSWORD=testing \
    -e MYSQL_DATABASE=testing \
    -p 3306:3306 \
    -d \
    mysql:8.0
```

To install PostgreSQL with Docker

```bash
docker run --name postgresql \
    -e POSTGRES_USER=testing \
    -e POSTGRES_PASSWORD=testing \
    -e POSTGRES_DB=testing \
    -p 5432:5432 \
    -d \
    postgres:15.4
```

To install SQL Server with Docker

> [!WARNING]
>
> If you have an error like this: "An invalid attribute was designated on the PDO object", you have to update `msphpsql` driver. Check <https://github.com/laravel/framework/issues/47937> for more information.

```bash
docker run -e "ACCEPT_EULA=Y" -e "MSSQL_SA_PASSWORD=12345OHdf%e" \
  -p 1433:1433 \
  --name sqlserver \
  --hostname sqlserver \
  -d \
  mcr.microsoft.com/mssql/server:2022-latest
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

-   [Spatie](https://github.com/spatie): for [`spatie/package-skeleton-laravel`](https://github.com/spatie/package-skeleton-laravel)
-   [Ewilan Riviere](https://github.com/ewilan-riviere): Author package

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[<img src="https://user-images.githubusercontent.com/48261459/201463225-0a5a084e-df15-4b11-b1d2-40fafd3555cf.svg" height="120rem" width="100%" />](https://github.com/kiwilan)

[version-src]: https://img.shields.io/packagist/v/kiwilan/typescriptable-laravel.svg?style=flat-square&colorA=18181B&colorB=777BB4
[version-href]: https://packagist.org/packages/kiwilan/typescriptable-laravel
[php-version-src]: https://img.shields.io/static/v1?style=flat-square&label=PHP&message=v8.1&color=777BB4&logo=php&logoColor=ffffff&labelColor=18181b
[php-version-href]: https://www.php.net/
[npm-version-src]: https://img.shields.io/npm/v/@kiwilan/typescriptable-laravel.svg?style=flat-square&color=CB3837&logoColor=ffffff&labelColor=18181b
[npm-version-href]: https://www.npmjs.com/package/@kiwilan/typescriptable-laravel
[downloads-src]: https://img.shields.io/packagist/dt/kiwilan/typescriptable-laravel.svg?style=flat-square&colorA=18181B&colorB=777BB4
[downloads-href]: https://packagist.org/packages/kiwilan/typescriptable-laravel
[license-src]: https://img.shields.io/github/license/kiwilan/typescriptable-laravel.svg?style=flat-square&colorA=18181B&colorB=777BB4
[license-href]: https://github.com/kiwilan/typescriptable-laravel/blob/main/README.md
[tests-src]: https://img.shields.io/github/actions/workflow/status/kiwilan/typescriptable-laravel/run-tests.yml?branch=main&label=tests&style=flat-square&colorA=18181B
[tests-href]: https://github.com/kiwilan/typescriptable-laravel/actions/workflows/run-tests.yml
[codecov-src]: https://codecov.io/gh/kiwilan/typescriptable-laravel/branch/main/graph/badge.svg?token=P9XIK2KV9G
[codecov-href]: https://codecov.io/gh/kiwilan/typescriptable-laravel
[laravel-src]: https://img.shields.io/static/v1?label=Laravel&message=v9&style=flat-square&colorA=18181B&colorB=FF2D20
[laravel-href]: https://laravel.com
