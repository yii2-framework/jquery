<?php

declare(strict_types=1);

namespace yii\jquery\widgets;

use yii\base\BaseObject;
use yii\helpers\Json;
use yii\web\client\ClientScriptInterface;
use yii\web\View;

use function is_string;

/**
 * jQuery client script for the [[Pjax]] widget.
 *
 * Registers the [jquery-pjax](https://github.com/yiisoft/jquery-pjax) plugin asset and
 * emits the initialization JavaScript using the jQuery API.
 *
 * @implements ClientScriptInterface<\yii\jquery\widgets\Pjax>
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
class PjaxJqueryClientScript extends BaseObject implements ClientScriptInterface
{
    public function getClientOptions(BaseObject $widget, array $params = []): array
    {
        return $widget->clientOptions ?? [];
    }

    public function register(BaseObject $widget, View $view, array $params = []): void
    {
        $id = $widget->options['id'];
        $js = '';

        $options = Json::htmlEncode($widget->clientOptions);

        if ($widget->linkSelector !== false) {
            $id = is_string($id) && $id !== '' ? $id :'';

            $linkSelector = Json::htmlEncode(
                $widget->linkSelector !== null ? $widget->linkSelector : "#{$id} a",
            );

            $js .= "jQuery(document).pjax($linkSelector, $options);";
        }

        if ($widget->formSelector !== false) {
            $id = is_string($id) && $id !== '' ? $id :'';

            $formSelector = Json::htmlEncode(
                $widget->formSelector !== null ? $widget->formSelector : "#{$id} form[data-pjax]",
            );
            $submitEvent = Json::htmlEncode($widget->submitEvent);

            $js .= "\njQuery(document).off($submitEvent, $formSelector).on($submitEvent, $formSelector, function (event) {jQuery.pjax.submit(event, $options);});";
        }

        PjaxAsset::register($view);

        if ($js !== '') {
            $view->registerJs($js);
        }
    }
}
