# Upgrade notes

## 0.1.0 Under development

### Initial release — extracted from `yii2-framework/yii2`

This package is the jQuery integration layer for [`yii2-framework/yii2`](https://github.com/yii2-framework/yii2).

It was extracted from yii2 to make the framework client-side agnostic. Install it to restore jQuery-backed client
validation, asset bundles, and widget scripts that were previously bundled with the framework.

#### Requirements

- PHP `8.2+`
- `yii2-framework/yii2: ^0.1@dev`

#### Installation

```bash
composer require yii2-framework/jquery
```

#### Configuration

Register the bootstrap class in `config/web.php`:

```php
return [
    'bootstrap' => [\yii\jquery\Bootstrap::class],
    // ...
];
```

#### Namespace changes from yii2

All classes in this package use the `yii\jquery\*` namespace hierarchy. The corresponding classes that previously
shipped with yii2 used framework namespaces (`yii\web\`, `yii\widgets\`, etc.). Update any direct class references:

| Before (yii2)                    | After (this package)                    |
| -------------------------------- | --------------------------------------- |
| `yii\web\JqueryAsset`            | `yii\jquery\web\JqueryAsset`            |
| `yii\validators\ValidationAsset` | `yii\jquery\validators\ValidationAsset` |
| `yii\widgets\ActiveFormAsset`    | `yii\jquery\widgets\ActiveFormAsset`    |
| `yii\widgets\PjaxAsset`          | `yii\jquery\widgets\PjaxAsset`          |
| `yii\widgets\MaskedInputAsset`   | `yii\jquery\widgets\MaskedInputAsset`   |
| `yii\captcha\CaptchaAsset`       | `yii\jquery\captcha\CaptchaAsset`       |
| `yii\grid\GridViewAsset`         | `yii\jquery\grid\GridViewAsset`         |

Direct references to these classes in application code are uncommon; they are registered automatically when jQuery
support is active. **No action required** for standard Yii2 applications.

### Package positioning

`yii2-framework/jquery` should be treated as the legacy optional integration for applications that still depend on
classic Yii2 client-side behavior.

For frontend modernization projects, the recommended migration path is to keep this package only on legacy routes and
introduce a separate Inertia-based package family for new pages. This repository does not provide those packages, but
it is designed to coexist with them because `yii2-framework/yii2` already supports strategy-based client integrations.

### jQuery compatibility

This package supports jQuery `3.7.1` and jQuery `4.0.0`.

- jQuery `3.7.1` remains the default dependency line for `0.1.x`.
- jQuery `4.0.0` is supported when the host application pins it explicitly in its project-level `package.json`.

The package requires the full jQuery build because it uses Ajax and Deferred APIs.
