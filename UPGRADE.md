# Upgrade notes

## 1.0.0 Under development

### Initial release — extracted from `yii2-framework/core`

This package is the jQuery integration layer for [`yii2-framework/core`](https://github.com/yii2-framework/core).

It was extracted from core to make the framework client-side agnostic. Install it to restore jQuery-backed client
validation, asset bundles, and widget scripts that were previously bundled with the framework.

#### Requirements

- PHP 8.2+
- `yii2-framework/core: ^1.0`

#### Installation

```bash
composer require yii2-framework/jquery
```

#### Configuration

Register the bootstrap class in `config/web.php`:

```php
return [
    'bootstrap' => [\yii\jquery\Bootstrap::class],
    'useJquery' => true,
    // ...
];
```

#### Namespace changes from core

All classes in this package use the `yii\jquery\*` namespace hierarchy. The corresponding classes that previously
shipped with core used framework namespaces (`yii\web\`, `yii\widgets\`, etc.). Update any direct class references:

| Before (core) | After (this package) |
| --- | --- |
| `yii\web\JqueryAsset` | `yii\jquery\web\JqueryAsset` |
| `yii\validators\ValidationAsset` | `yii\jquery\validators\ValidationAsset` |
| `yii\widgets\ActiveFormAsset` | `yii\jquery\widgets\ActiveFormAsset` |
| `yii\widgets\PjaxAsset` | `yii\jquery\widgets\PjaxAsset` |
| `yii\widgets\MaskedInputAsset` | `yii\jquery\widgets\MaskedInputAsset` |
| `yii\captcha\CaptchaAsset` | `yii\jquery\captcha\CaptchaAsset` |
| `yii\grid\GridViewAsset` | `yii\jquery\grid\GridViewAsset` |

Direct references to these classes in application code are uncommon — they are registered automatically when jQuery
support is active. **No action required** for standard Yii2 applications.
