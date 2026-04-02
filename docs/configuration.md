# Configuration reference

## Overview

`yii2-framework/jquery` is an optional strategy package. It does not replace `yii2-framework/yii2`; instead, it
provides jQuery-backed client integrations for widgets and validators that expose a `$clientScript` strategy property.

## Basic configuration

Enable the package through the application bootstrap:

```php
// config/web.php
return [
    'bootstrap' => [\yii\jquery\Bootstrap::class],
];
```

When enabled, the package configures jQuery-based client scripts for:

- `yii\widgets\ActiveForm`
- `yii\jquery\widgets\MaskedInput`
- `yii\jquery\widgets\Pjax`
- `yii\captcha\Captcha`
- `yii\captcha\CaptchaValidator`
- `yii\grid\GridView`
- `yii\grid\CheckboxColumn`
- the built-in validators that support `ClientValidatorScriptInterface`

## Overriding a single validator strategy

Use a custom client strategy for one rule without changing the global bootstrap behavior:

```php
public function rules(): array
{
    return [
        [
            'email',
            'required',
            'clientScript' => [
                'class' => MyCustomRequiredClientScript::class,
            ],
        ],
    ];
}
```

## Overriding a single widget strategy

Widgets may also receive a custom client strategy directly:

```php
echo \yii\widgets\ActiveForm::begin(
    [
        'clientScript' => [
            'class' => MyActiveFormClientScript::class,
        ],
    ],
);
```

## Leaving a component client-side agnostic

To avoid registering a client strategy for a specific widget or validator instance, set `clientScript` to `null` and do
not bootstrap this package globally for that use case.

This is useful when you are migrating route by route and want a page to remain free of jQuery-backed behavior.

## Next steps

- 📚 [Installation Guide](installation.md)
- 💡 [Usage Examples](examples.md)
- 🧪 [Testing Guide](testing.md)
