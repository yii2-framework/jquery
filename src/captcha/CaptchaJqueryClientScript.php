<?php

declare(strict_types=1);

namespace yii\jquery\captcha;

use yii\base\BaseObject;
use yii\helpers\Json;
use yii\web\client\ClientScriptInterface;
use yii\web\View;

use function is_string;

/**
 * jQuery client script for the [[Captcha]] widget.
 *
 * Registers the CAPTCHA asset bundle and emits the `yiiCaptcha` jQuery plugin
 * initialization JavaScript.
 *
 * @implements ClientScriptInterface<\yii\captcha\Captcha>
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
class CaptchaJqueryClientScript extends BaseObject implements ClientScriptInterface
{
    public function getClientOptions(BaseObject $widget, array $params = []): array
    {
        return $widget->getClientOptions();
    }

    /**
     * @param \yii\captcha\Captcha $widget
     */
    public function register(BaseObject $widget, View $view, array $params = []): void
    {
        $options = $widget->getClientOptions();

        $options = $options === [] ? '' : Json::htmlEncode($options);

        $id = $widget->imageOptions['id'];

        CaptchaAsset::register($view);

        if (is_string($id) && $id !== '') {
            $view->registerJs("jQuery('#$id').yiiCaptcha($options);");
        }
    }
}
