# Installation guide

## System requirements

- [PHP](https://www.php.net/downloads) `8.2` or higher.
- [Composer](https://getcomposer.org/download/) for dependency management.
- A project-level `node_modules` directory exposed through the `@npm` alias.

## Installation

### Method 1: Using [Composer](https://getcomposer.org/download/) (recommended)

Install the extension.

```bash
composer require yii2-framework/jquery:^0.1
```

### Method 2: Manual installation

Add to your `composer.json`.

```json
{
    "require": {
        "yii2-framework/jquery": "^0.1"
    }
}
```

Then run.

```bash
composer update
```

## Allow automatic npm dependency installation

This package uses [`php-forge/foxy`](https://github.com/php-forge/foxy) to merge npm dependencies during Composer
operations. Make sure the Composer plugin is allowed:

```json
{
    "config": {
        "allow-plugins": {
            "php-forge/foxy": true
        }
    }
}
```

If the npm dependencies are missing after installation, run:

```bash
composer update
```

## Register the bootstrap integration

Enable the jQuery strategy package in your web configuration:

```php
// config/web.php
return [
    'bootstrap' => [\yii\jquery\Bootstrap::class],
];
```

This bootstrap registers jQuery-based `$clientScript` defaults for validators and widgets that support strategy-based
client integrations.

## When not to install this package

Do not install `yii2-framework/jquery` for new applications that are intentionally avoiding jQuery.

In that scenario, prefer keeping the application client-side agnostic or introducing a separate frontend integration
layer for modern pages.

## Next steps

- ⚙️ [Configuration Reference](configuration.md)
- 💡 [Usage Examples](examples.md)
- 🧪 [Testing Guide](testing.md)
