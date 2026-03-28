<!-- markdownlint-disable MD041 -->
<p align="center">
    <picture>
        <source media="(prefers-color-scheme: dark)" srcset="https://www.yiiframework.com/image/design/logo/yii3_full_for_dark.svg">
        <source media="(prefers-color-scheme: light)" srcset="https://www.yiiframework.com/image/design/logo/yii3_full_for_light.svg">
        <img src="https://www.yiiframework.com/image/design/logo/yii3_full_for_dark.svg" alt="Yii Framework" width="80%">
    </picture>
    <h1 align="center">jQuery</h1>
    <br>
</p>
<!-- markdownlint-enable MD041 -->

<p align="center">
    <a href="https://github.com/yii2-framework/jquery/actions/workflows/build.yml" target="_blank">
        <img src="https://img.shields.io/github/actions/workflow/status/yii2-framework/jquery/build.yml?style=for-the-badge&logo=github&label=PHPUnit" alt="PHPUnit">
    </a>
    <a href="https://dashboard.stryker-mutator.io/reports/github.com/yii2-framework/jquery/main" target="_blank">
        <img src="https://img.shields.io/endpoint?style=for-the-badge&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fyii2-framework%2Fjquery%2Fmain" alt="Mutation Testing">
    </a>
    <a href="https://github.com/yii2-framework/jquery/actions/workflows/static.yml" target="_blank">
        <img src="https://img.shields.io/github/actions/workflow/status/yii2-framework/jquery/static.yml?style=for-the-badge&logo=github&label=PHPStan" alt="PHPStan">
    </a>
</p>

<p align="center">
    <strong>Optional jQuery integration layer for <a href="https://github.com/yii2-framework/core">yii2-framework/core</a></strong><br>
    <em>Asset bundles, client-side validation scripts, and widget client scripts — all jQuery-backed</em>
</p>

## Features

<picture>
    <source media="(min-width: 768px)" srcset="./docs/svgs/features.svg">
    <img src="./docs/svgs/features-mobile.svg" alt="Feature Overview" style="width: 100%;">
</picture>

## Quick start

### Installation

```bash
composer require yii2-framework/jquery
```

### Asset installation

This package uses [php-forge/foxy](https://github.com/php-forge/foxy) to install npm dependencies (jQuery, Inputmask,
etc.) automatically during `composer install` or `composer update`.

The `@npm` alias must point to your project's `node_modules` directory:

```php
// config/web.php
return [
    'aliases' => [
        '@npm' => dirname(__DIR__) . '/node_modules',
    ],
    // ...
];
```

If npm packages are not installed automatically, verify that:

1. `php-forge/foxy` is allowed in your `composer.json`:

```json
{
    "config": {
        "allow-plugins": {
            "php-forge/foxy": true
        }
    }
}
```

2. Run `composer update` to trigger the asset merge.

### Configuration

Register the bootstrap class in your application configuration:

```php
// config/web.php
return [
    'bootstrap' => [\yii\jquery\Bootstrap::class],
    // ...
];
```

`Bootstrap` configures the DI container with jQuery-based `$clientScript` defaults for all validators and widgets
that support the strategy pattern. No other configuration is required.

### Overriding a single validator

```php
public function rules(): array
{
    return [
        [
            'email',
            'required',
            'clientScript' => ['class' => MyCustomRequiredClientScript::class],
        ],
    ];
}
```

## Package information

[![PHP](https://img.shields.io/badge/%3E%3D8.2-777BB4.svg?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/releases/8.2/en.php)
[![Latest Stable Version](https://img.shields.io/packagist/v/yii2-framework/jquery.svg?style=for-the-badge&logo=packagist&logoColor=white&label=Stable)](https://packagist.org/packages/yii2-framework/jquery)
[![Total Downloads](https://img.shields.io/packagist/dt/yii2-framework/jquery.svg?style=for-the-badge&logo=composer&logoColor=white&label=Downloads)](https://packagist.org/packages/yii2-framework/jquery)

## Quality code

[![Codecov](https://img.shields.io/codecov/c/github/yii2-framework/jquery.svg?style=for-the-badge&logo=codecov&logoColor=white&label=Coverage)](https://codecov.io/github/yii2-framework/jquery)
[![PHPStan Level Max](https://img.shields.io/badge/PHPStan-Level%20Max-4F5D95.svg?style=for-the-badge&logo=php&logoColor=white)](https://github.com/yii2-framework/jquery/actions/workflows/static.yml)
[![Super-Linter](https://img.shields.io/github/actions/workflow/status/yii2-framework/jquery/linter.yml?style=for-the-badge&label=Super-Linter&logo=github)](https://github.com/yii2-framework/jquery/actions/workflows/linter.yml)
[![StyleCI](https://img.shields.io/badge/StyleCI-Passed-44CC11.svg?style=for-the-badge&logo=styleci&logoColor=white)](https://github.styleci.io/repos/yii2-framework/jquery?branch=main)

## Our social networks

[![Follow on X](https://img.shields.io/badge/-Follow%20on%20X-1DA1F2.svg?style=for-the-badge&logo=x&logoColor=white&labelColor=000000)](https://x.com/Terabytesoftw)

## License

[![License](https://img.shields.io/badge/License-BSD--3--Clause-brightgreen.svg?style=for-the-badge&logo=opensourceinitiative&logoColor=white&labelColor=555555)](LICENSE)
