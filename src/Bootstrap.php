<?php

declare(strict_types=1);

namespace yii\jquery;

use Yii;
use yii\base\BootstrapInterface;
use yii\captcha\Captcha;
use yii\captcha\CaptchaValidator;
use yii\grid\CheckboxColumn;
use yii\grid\GridView;
use yii\jquery\widgets\MaskedInput;
use yii\jquery\widgets\Pjax;
use yii\validators\BooleanValidator;
use yii\validators\CompareValidator;
use yii\validators\EmailValidator;
use yii\validators\FileValidator;
use yii\validators\FilterValidator;
use yii\validators\ImageValidator;
use yii\validators\IpValidator;
use yii\validators\NumberValidator;
use yii\validators\RangeValidator;
use yii\validators\RegularExpressionValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\validators\TrimValidator;
use yii\validators\UrlValidator;
use yii\widgets\ActiveForm;

/**
 * Bootstraps the jQuery integration layer.
 *
 * Configures the DI container with jQuery-based `$clientScript` defaults for all validators and widgets that support
 * the strategy pattern.
 *
 * Register this class in `config/web.php` or via the `bootstrap` key in `composer.json` extra when using
 * `yiisoft/yii2-composer`.
 *
 * Usage example:
 *
 * ```php
 * // config/web.php
 * 'bootstrap' => [\yii\jquery\Bootstrap::class],
 * ```
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
final class Bootstrap implements BootstrapInterface
{
    /**
     * Maps core validators, widgets, and grid components to their jQuery client-script implementations.
     *
     * Each key is a fully qualified class name from `yii2-framework/yii2`, and each value is the corresponding jQuery
     * client-script class from this package. During {@see bootstrap()}, these are registered as DI container defaults
     * so that `$clientScript` is automatically configured when the component is instantiated.
     */
    private const CLIENT_SCRIPT_MAP = [
        // Captcha.
        Captcha::class => \yii\jquery\captcha\CaptchaJqueryClientScript::class,
        CaptchaValidator::class => \yii\jquery\captcha\CaptchaValidatorJqueryClientScript::class,
        // Grid.
        CheckboxColumn::class => grid\CheckboxColumnJqueryClientScript::class,
        GridView::class => grid\GridViewJqueryClientScript::class,
        // Validators.
        BooleanValidator::class => validators\BooleanValidatorJqueryClientScript::class,
        CompareValidator::class => validators\CompareValidatorJqueryClientScript::class,
        EmailValidator::class => validators\EmailValidatorJqueryClientScript::class,
        FileValidator::class => validators\FileValidatorJqueryClientScript::class,
        FilterValidator::class => validators\FilterValidatorJqueryClientScript::class,
        ImageValidator::class => validators\ImageValidatorJqueryClientScript::class,
        IpValidator::class => validators\IpValidatorJqueryClientScript::class,
        NumberValidator::class => validators\NumberValidatorJqueryClientScript::class,
        RangeValidator::class => validators\RangeValidatorJqueryClientScript::class,
        RegularExpressionValidator::class => validators\RegularExpressionValidatorJqueryClientScript::class,
        RequiredValidator::class => validators\RequiredValidatorJqueryClientScript::class,
        StringValidator::class => validators\StringValidatorJqueryClientScript::class,
        TrimValidator::class => validators\TrimValidatorJqueryClientScript::class,
        UrlValidator::class => validators\UrlValidatorJqueryClientScript::class,
        // Widgets.
        ActiveForm::class => widgets\ActiveFormJqueryClientScript::class,
        MaskedInput::class => widgets\MaskedInputJqueryClientScript::class,
        Pjax::class => widgets\PjaxJqueryClientScript::class,
    ];

    public function bootstrap($app): void
    {
        foreach (self::CLIENT_SCRIPT_MAP as $component => $clientScript) {
            Yii::$container->set($component, ['clientScript' => ['class' => $clientScript]]);
        }
    }
}
