<?php

declare(strict_types=1);

namespace yii\jquery\tests\validators;

use PHPUnit\Framework\Attributes\Group;
use Yii;
use yii\jquery\tests\data\validators\FakedValidationModel;
use yii\jquery\tests\TestCase;
use yii\jquery\validators\ValidationAsset;
use yii\validators\NumberValidator;
use yii\web\JsExpression;
use yii\web\View;

/**
 * Unit tests for {@see NumberValidatorJqueryClientScript} jQuery client-side validation script.
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
#[Group('jquery')]
#[Group('validators')]
final class NumberValidatorJqueryClientScriptTest extends TestCase
{
    public function testClientValidateAttribute(): void
    {
        $modelValidator = new FakedValidationModel();

        $validator = Yii::createObject(['class' => NumberValidator::class]);

        $modelValidator->attrA = 123.45;

        self::assertSame(
            <<<JS
            yii.validation.number(value, messages, {"pattern":/^[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?$/,"message":"attrA must be a number.","skipOnEmpty":1});
            JS,
            $validator->clientValidateAttribute($modelValidator, 'attrA', Yii::$app->view),
            'Should return correct validation script.',
        );

        $clientOptions = $validator->getClientOptions($modelValidator, 'attrA');

        $clientOptions['pattern'] = $clientOptions['pattern'] instanceof JsExpression
            ? (string) $clientOptions['pattern']
            : '';

        self::assertSame(
            [
                'pattern' => $validator->numberPattern,
                'message' => 'attrA must be a number.',
                'skipOnEmpty' => 1,
            ],
            $clientOptions,
            "Should return correct options 'array'.",
        );

        $errorMessage = null;

        $validator->validate('invalid-number', $errorMessage);

        self::assertSame(
            'the input value must be a number.',
            $errorMessage,
            'Error message should match expected output.',
        );
    }

    /**
     * @see https://github.com/yiisoft/yii2/issues/3118
     */
    public function testClientValidateComparison(): void
    {
        $val = Yii::createObject(
            [
                'class' => NumberValidator::class,
                'min' => 5,
                'max' => 10,
            ],
        );

        $model = new FakedValidationModel();

        $js = $val->clientValidateAttribute(
            $model,
            'attr_number',
            new View(['assetBundles' => [ValidationAsset::class => true]]),
        );

        self::assertSame(
            <<<JS
            yii.validation.number(value, messages, {"pattern":/^[-+]?[0-9]*\\.?[0-9]+([eE][-+]?[0-9]+)?$/,"message":"attr_number must be a number.","min":5,"tooSmall":"attr_number must be no less than 5.","max":10,"tooBig":"attr_number must be no greater than 10.","skipOnEmpty":1});
            JS,
            $js,
            "Should return correct validation script with 'integer' min/max constraints.",
        );

        $val = Yii::createObject(
            [
                'class' => NumberValidator::class,
                'min' => '5',
                'max' => '10',
            ],
        );

        $model = new FakedValidationModel();

        $js = $val->clientValidateAttribute(
            $model,
            'attr_number',
            new View(['assetBundles' => [ValidationAsset::class => true]]),
        );

        self::assertSame(
            <<<JS
            yii.validation.number(value, messages, {"pattern":/^[-+]?[0-9]*\\.?[0-9]+([eE][-+]?[0-9]+)?$/,"message":"attr_number must be a number.","min":5,"tooSmall":"attr_number must be no less than 5.","max":10,"tooBig":"attr_number must be no greater than 10.","skipOnEmpty":1});
            JS,
            $js,
            "Should return correct validation script with 'string' min/max constraints.",
        );

        $val = Yii::createObject(
            [
                'class' => NumberValidator::class,
                'min' => 5.65,
                'max' => 13.37,
            ],
        );

        $model = new FakedValidationModel();

        $js = $val->clientValidateAttribute(
            $model,
            'attr_number',
            new View(['assetBundles' => [ValidationAsset::class => true]]),
        );

        self::assertSame(
            <<<JS
            yii.validation.number(value, messages, {"pattern":/^[-+]?[0-9]*\\.?[0-9]+([eE][-+]?[0-9]+)?$/,"message":"attr_number must be a number.","min":5.65,"tooSmall":"attr_number must be no less than 5.65.","max":13.37,"tooBig":"attr_number must be no greater than 13.37.","skipOnEmpty":1});
            JS,
            $js,
            "Should return correct validation script with 'float' min/max constraints.",
        );

        $val = Yii::createObject(
            [
                'class' => NumberValidator::class,
                'min' => '5.65',
                'max' => '13.37',
            ],
        );

        $model = new FakedValidationModel();

        $js = $val->clientValidateAttribute(
            $model,
            'attr_number',
            new View(['assetBundles' => [ValidationAsset::class => true]]),
        );

        self::assertSame(
            <<<JS
            yii.validation.number(value, messages, {"pattern":/^[-+]?[0-9]*\\.?[0-9]+([eE][-+]?[0-9]+)?$/,"message":"attr_number must be a number.","min":5.65,"tooSmall":"attr_number must be no less than 5.65.","max":13.37,"tooBig":"attr_number must be no greater than 13.37.","skipOnEmpty":1});
            JS,
            $js,
            "Should return correct validation script with 'string' float min/max constraints.",
        );
    }
}
