<?php

declare(strict_types=1);

namespace yii\jquery\validators;

use yii\base\BaseObject;
use yii\base\Model;
use yii\helpers\Json;
use yii\validators\client\ClientValidatorScriptInterface;
use yii\validators\FilterValidator;
use yii\validators\Validator;
use yii\web\View;

/**
 * jQuery client-side script for [[FilterValidator]] when the filter is `'trim'`.
 *
 * @implements ClientValidatorScriptInterface<FilterValidator>
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
class FilterValidatorJqueryClientScript extends BaseObject implements ClientValidatorScriptInterface
{
    /**
     * @phpstan-return array{skipOnEmpty?: int}
     */
    public function getClientOptions(Validator $validator, Model $model, string $attribute): array
    {
        $options = [];

        if ($validator->skipOnEmpty) {
            $options['skipOnEmpty'] = 1;
        }

        return $options;
    }

    public function register(Validator $validator, Model $model, string $attribute, View $view): string|null
    {
        if ($validator->filter !== 'trim') {
            return null;
        }

        ValidationAsset::register($view);

        $options = $this->getClientOptions($validator, $model, $attribute);

        return 'value = yii.validation.trim($form, attribute, ' . Json::htmlEncode($options) . ', value);';
    }
}
