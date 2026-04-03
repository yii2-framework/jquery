# Usage examples

## Enable the legacy jQuery layer for a classic Yii2 application

```php
// config/web.php
return [
    'aliases' => [
        '@npm' => dirname(__DIR__) . '/node_modules',
    ],
    'bootstrap' => [
        \yii\jquery\Bootstrap::class,
    ],
];
```

## Override one validator strategy

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

## Override one widget strategy

```php
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(
    [
        'clientScript' => ['class' => MyActiveFormClientScript::class],
    ]
);
```

## Keep jQuery only on legacy routes

A practical modernization strategy is to keep this package enabled only for controllers or layouts that still render
classic Yii2 pages and widgets.

Example characteristics of a legacy page that should keep this package:

- `ActiveForm` client-side validation is still required;
- `GridView` filtering is still powered by classic GET forms;
- `Pjax` is still used for partial page refreshes;
- the page depends on `yii.js` features such as `data-method` or `data-confirm`.

## Opt in to jQuery 4 in the host application

The package defaults to jQuery `3.7.1`. If your application is ready for jQuery `4.0.0`, pin it explicitly in the
application `package.json`:

```json
{
    "dependencies": {
        "jquery": "^4.0.0"
    }
}
```

## Move new pages to a separate frontend integration

Do not port `yii.activeForm.js`, `yii.validation.js`, `yii.gridView.js`, or pjax semantics to a new stack one
widget at a time.

## Next steps

- 📚 [Installation Guide](installation.md)
- ⚙️ [Configuration Reference](configuration.md)
- 🧪 [Testing Guide](testing.md)
