<?php

declare(strict_types=1);

namespace yii\jquery\captcha;

use yii\base\BaseObject;
use yii\base\Model;
use yii\captcha\CaptchaValidator;
use yii\helpers\Json;
use yii\jquery\validators\ValidationAsset;
use yii\validators\client\ClientValidatorScriptInterface;
use yii\validators\Validator;
use yii\web\View;

/**
 * jQuery client-side script for [[CaptchaValidator]].
 *
 * @implements ClientValidatorScriptInterface<CaptchaValidator>
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
class CaptchaValidatorJqueryClientScript extends BaseObject implements ClientValidatorScriptInterface
{
    /**
     * @phpstan-return array{hash: int, hashKey: string, caseSensitive: bool, message: string, skipOnEmpty?: int}
     */
    public function getClientOptions(Validator $validator, Model $model, string $attribute): array
    {
        return $validator->getClientOptions($model, $attribute);
    }

    public function register(Validator $validator, Model $model, string $attribute, View $view): string
    {
        ValidationAsset::register($view);

        $options = $this->getClientOptions($validator, $model, $attribute);

        return 'yii.validation.captcha(value, messages, ' . Json::htmlEncode($options) . ');';
    }
}
