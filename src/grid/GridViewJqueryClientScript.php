<?php

declare(strict_types=1);

namespace yii\jquery\grid;

use Closure;
use Yii;
use yii\base\BaseObject;
use yii\grid\GridView;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\client\ClientScriptInterface;
use yii\web\View;

use function is_string;

/**
 * jQuery client-side script for [[GridView]].
 *
 * Registers the `yii.gridView` jQuery plugin and encodes filtering options.
 *
 * @implements ClientScriptInterface<GridView>
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
class GridViewJqueryClientScript extends BaseObject implements ClientScriptInterface
{
    public function getClientOptions(BaseObject $widget, array $params = []): array
    {
        $filterUrl = $widget->filterUrl ?? Yii::$app->request->url;
        $filterSelector = '';

        $id = $widget->filterRowOptions['id'];

        if (is_string($id) && $id !== '') {
            $filterSelector = "#$id input, #$id select";
        }

        if ($widget->filterSelector !== null) {
            $additionalFilterSelector = $widget->filterSelector;

            if ($widget->filterSelector instanceof Closure) {
                $additionalFilterSelector = ($widget->filterSelector)($widget->getId(), $id);
            }

            $filterSelector .= ", {$additionalFilterSelector}";

            if ($widget->overrideFilterSelector) {
                $filterSelector = $additionalFilterSelector;
            }
        }

        return [
            'filterUrl' => Url::to($filterUrl),
            'filterSelector' => $filterSelector,
        ];
    }

    public function register(BaseObject $widget, View $view, array $params = []): void
    {
        /** @var GridView $widget */
        GridViewAsset::register($view);

        $id = $widget->options['id'];

        $options = Json::htmlEncode(
            [
                ...$this->getClientOptions($widget),
                'filterOnFocusOut' => $widget->filterOnFocusOut,
            ],
        );

        if (is_string($id) && $id !== '') {
            $view->registerJs("jQuery('#$id').yiiGridView($options);");
        }
    }
}
