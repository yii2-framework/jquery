<?php

declare(strict_types=1);

namespace yii\jquery\tests\validators;

use PHPUnit\Framework\Attributes\Group;
use Yii;
use yii\jquery\tests\data\validators\FakedValidationModel;
use yii\jquery\tests\TestCase;
use yii\validators\BooleanValidator;

/**
 * Unit tests for {@see BooleanValidatorJqueryClientScript} jQuery client-side validation script.
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
#[Group('jquery')]
#[Group('validators')]
final class BooleanValidatorJqueryClientScriptTest extends TestCase
{
    public function testClientValidateAttribute(): void
    {
        $modelValidator = new FakedValidationModel();

        $validator = Yii::createObject(
            [
                'class' => BooleanValidator::class,
                'trueValue' => true,
                'falseValue' => false,
                'strict' => true,
            ],
        );

        $modelValidator->attrA = true;

        self::assertSame(
            <<<JS
            yii.validation.boolean(value, messages, {"trueValue":true,"falseValue":false,"message":"attrA must be either \u0022true\u0022 or \u0022false\u0022.","skipOnEmpty":1,"strict":1});
            JS,
            $validator->clientValidateAttribute($modelValidator, 'attrA', Yii::$app->view),
            'Should return correct validation script.',
        );
        self::assertSame(
            [
                'trueValue' => true,
                'falseValue' => false,
                'message' => 'attrA must be either "true" or "false".',
                'skipOnEmpty' => 1,
                'strict' => 1,
            ],
            $validator->getClientOptions($modelValidator, 'attrA'),
            'Should return correct options array.',
        );

        $errorMessage = null;

        $validator->validate('someIncorrectValue', $errorMessage);

        self::assertSame(
            'the input value must be either "true" or "false".',
            $errorMessage,
            'Error message should match expected output.',
        );
    }
}
