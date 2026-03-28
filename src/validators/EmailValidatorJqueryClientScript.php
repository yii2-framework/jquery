<?php

declare(strict_types=1);

namespace yii\jquery\validators;

use yii\base\BaseObject;
use yii\base\Model;
use yii\helpers\Json;
use yii\validators\client\ClientValidatorScriptInterface;
use yii\validators\EmailValidator;
use yii\validators\Validator;
use yii\web\JsExpression;
use yii\web\View;

/**
 * jQuery client-side script for [[EmailValidator]].
 *
 * @implements ClientValidatorScriptInterface<EmailValidator>
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
class EmailValidatorJqueryClientScript extends BaseObject implements ClientValidatorScriptInterface
{
    /**
     * @phpstan-return array{
     *   pattern: JsExpression,
     *   fullPattern: JsExpression,
     *   allowName: bool,
     *   message: string,
     *   enableIDN: bool,
     *   skipOnEmpty?: int,
     * }
     */
    public function getClientOptions(Validator $validator, Model $model, string $attribute): array
    {
        $options = [
            'pattern' => new JsExpression($validator->pattern),
            'fullPattern' => new JsExpression($validator->fullPattern),
            'allowName' => $validator->allowName,
            'message' => $validator->getFormattedClientMessage(
                $validator->message,
                ['attribute' => $model->getAttributeLabel($attribute)],
            ),
            'enableIDN' => $validator->enableIDN,
        ];

        if ($validator->skipOnEmpty) {
            $options['skipOnEmpty'] = 1;
        }

        return $options;
    }

    public function register(Validator $validator, Model $model, string $attribute, View $view): string
    {
        ValidationAsset::register($view);

        if ($validator->enableIDN) {
            PunycodeAsset::register($view);
        }

        $options = $this->getClientOptions($validator, $model, $attribute);

        return 'yii.validation.email(value, messages, ' . Json::htmlEncode($options) . ');';
    }
}
