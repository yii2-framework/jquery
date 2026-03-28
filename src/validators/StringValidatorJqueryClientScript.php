<?php

declare(strict_types=1);

namespace yii\jquery\validators;

use yii\base\BaseObject;
use yii\base\Model;
use yii\helpers\Json;
use yii\validators\client\ClientValidatorScriptInterface;
use yii\validators\StringValidator;
use yii\validators\Validator;
use yii\web\View;

/**
 * jQuery client-side script for [[StringValidator]].
 *
 * @implements ClientValidatorScriptInterface<StringValidator>
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
class StringValidatorJqueryClientScript extends BaseObject implements ClientValidatorScriptInterface
{
    /**
     * @phpstan-return array{
     *   message: string,
     *   min?: int,
     *   tooShort?: string,
     *   max?: int,
     *   tooLong?: string,
     *   is?: int,
     *   notEqual?: string,
     *   skipOnEmpty?: int,
     * }
     */
    public function getClientOptions(Validator $validator, Model $model, string $attribute): array
    {
        $label = $model->getAttributeLabel($attribute);

        $options = [
            'message' => $validator->getFormattedClientMessage(
                $validator->message,
                ['attribute' => $label],
            ),
        ];

        if ($validator->min !== null) {
            $options['min'] = $validator->min;
            $options['tooShort'] = $validator->getFormattedClientMessage(
                $validator->tooShort,
                [
                    'attribute' => $label,
                    'min' => $validator->min,
                ],
            );
        }

        if ($validator->max !== null) {
            $options['max'] = $validator->max;
            $options['tooLong'] = $validator->getFormattedClientMessage(
                $validator->tooLong,
                [
                    'attribute' => $label,
                    'max' => $validator->max,
                ],
            );
        }

        if ($validator->length !== null) {
            $options['is'] = $validator->length;
            $options['notEqual'] = $validator->getFormattedClientMessage(
                $validator->notEqual,
                [
                    'attribute' => $label,
                    'length' => $validator->length,
                ],
            );
        }

        if ($validator->skipOnEmpty) {
            $options['skipOnEmpty'] = 1;
        }

        return $options;
    }

    public function register(Validator $validator, Model $model, string $attribute, View $view): string
    {
        ValidationAsset::register($view);

        $options = $this->getClientOptions($validator, $model, $attribute);

        return 'yii.validation.string(value, messages, ' . Json::htmlEncode($options) . ');';
    }
}
