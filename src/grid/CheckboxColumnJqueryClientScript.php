<?php

declare(strict_types=1);

namespace yii\jquery\grid;

use yii\base\BaseObject;
use yii\grid\CheckboxColumn;
use yii\helpers\Json;
use yii\web\client\ClientScriptInterface;
use yii\web\View;

use function is_string;

/**
 * jQuery client-side script for [[CheckboxColumn]].
 *
 * Registers the `yiiGridView('setSelectionColumn', ...)` jQuery plugin call.
 *
 * @implements ClientScriptInterface<CheckboxColumn>
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
class CheckboxColumnJqueryClientScript extends BaseObject implements ClientScriptInterface
{
    public function getClientOptions(BaseObject $widget, array $params = []): array
    {
        return [];
    }

    public function register(BaseObject $widget, View $view, array $params = []): void
    {
        $id = $widget->grid->options['id'];

        $options = Json::encode(
            [
                'name' => $widget->name,
                'class' => $widget->cssClass,
                'multiple' => $widget->multiple,
                'checkAll' => $widget->grid->showHeader ? $params['headerCheckBoxName'] ?? null : null,
            ],
        );

        if (is_string($id) && $id !== '') {
            $view->registerJs("jQuery('#$id').yiiGridView('setSelectionColumn', $options);");
        }
    }
}
