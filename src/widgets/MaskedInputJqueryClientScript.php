<?php

declare(strict_types=1);

namespace yii\jquery\widgets;

use yii\base\BaseObject;
use yii\helpers\Json;
use yii\web\client\ClientScriptInterface;
use yii\web\View;

use function is_array;
use function is_string;

/**
 * jQuery client script for the [[MaskedInput]] widget.
 *
 * Registers the [jQuery Input Mask](https://github.com/RobinHerbots/Inputmask) plugin asset and emits the plugin
 * initialization JavaScript using the jQuery API.
 *
 * Relies on [[MaskedInput::$hashVar]] and [[MaskedInput::$clientOptions]] being populated by
 * [[MaskedInput::registerClientScript()]] before this class is invoked.
 *
 * @implements ClientScriptInterface<MaskedInput>
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
class MaskedInputJqueryClientScript extends BaseObject implements ClientScriptInterface
{
    public function getClientOptions(BaseObject $widget, array $params = []): array
    {
        return $widget->clientOptions;
    }

    public function register(BaseObject $widget, View $view, array $params = []): void
    {
        $js = '';

        /** @var string $pluginName */
        $pluginName = $widget::PLUGIN_NAME;

        if (is_array($widget->definitions) && $widget->definitions !== []) {
            $js .= ucfirst($pluginName) . '.extendDefinitions(' . Json::htmlEncode($widget->definitions) . ');';
        }

        if (is_array($widget->aliases) && $widget->aliases !== []) {
            $js .= ucfirst($pluginName) . '.extendAliases(' . Json::htmlEncode($widget->aliases) . ');';
        }

        $id = $widget->options['id'] ?? '';

        if (is_string($id) && $id !== '') {
            $js .= 'jQuery("#' . $id . '").' . $pluginName . '(' . $widget->hashVar . ');';
        }

        MaskedInputAsset::register($view);

        if ($js !== '') {
            $view->registerJs($js);
        }
    }
}
