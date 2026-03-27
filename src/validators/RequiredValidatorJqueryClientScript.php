<?php

declare(strict_types=1);

namespace yii\jquery\validators;

use yii\base\BaseObject;
use yii\base\Model;
use yii\helpers\Json;
use yii\validators\client\ClientValidatorScriptInterface;
use yii\validators\Validator;
use yii\web\View;

/**
 * jQuery client-side script for [[RequiredValidator]].
 *
 * @implements ClientValidatorScriptInterface<\yii\validators\RequiredValidator>
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
class RequiredValidatorJqueryClientScript extends BaseObject implements ClientValidatorScriptInterface
{
    /**
     * @phpstan-return array{message: string, requiredValue?: mixed, strict?: int}
     */
    public function getClientOptions(Validator $validator, Model $model, string $attribute): array
    {
        $options = [];

        if ($validator->requiredValue !== null) {
            $options['message'] = $validator->getFormattedClientMessage(
                $validator->message,
                ['requiredValue' => $validator->requiredValue],
            );
            $options['requiredValue'] = $validator->requiredValue;
        } else {
            $options['message'] = $validator->message;
        }

        $options['message'] = $validator->getFormattedClientMessage(
            $options['message'],
            ['attribute' => $model->getAttributeLabel($attribute)],
        );

        if ($validator->strict) {
            $options['strict'] = 1;
        }

        return $options;
    }

    public function register(Validator $validator, Model $model, string $attribute, View $view): string
    {
        ValidationAsset::register($view);

        $options = $this->getClientOptions($validator, $model, $attribute);

        return 'yii.validation.required(value, messages, ' . Json::htmlEncode($options) . ');';
    }
}
