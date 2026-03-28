<?php

declare(strict_types=1);

namespace yii\jquery\tests\validators;

use PHPUnit\Framework\Attributes\Group;
use Yii;
use yii\jquery\tests\data\validators\FakedValidationModel;
use yii\jquery\tests\TestCase;
use yii\validators\TrimValidator;

/**
 * Unit tests for {@see TrimValidatorJqueryClientScript} jQuery client-side validation script.
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
#[Group('jquery')]
#[Group('validators')]
final class TrimValidatorJqueryClientScriptTest extends TestCase
{
    public function testClientValidateAttribute(): void
    {
        $modelValidator = new FakedValidationModel();

        $validator = Yii::createObject(['class' => TrimValidator::class]);

        $modelValidator->attrA = '  test value  ';

        self::assertSame(
            <<<'JS'
            value = yii.validation.trim($form, attribute, {"skipOnArray":false,"skipOnEmpty":false,"chars":false}, value);
            JS,
            $validator->clientValidateAttribute($modelValidator, 'attrA', Yii::$app->view),
            'Should return correct validation script.',
        );
        self::assertSame(
            [
                'skipOnArray' => false,
                'skipOnEmpty' => false,
                'chars' => false,
            ],
            $validator->getClientOptions($modelValidator, 'attrA'),
            "Should return correct options 'array'.",
        );

        $validator->validateAttribute($modelValidator, 'attrA');

        self::assertSame(
            'test value',
            $modelValidator->attrA,
            'Should trim the attribute value.',
        );
    }

    public function testClientValidateAttributeWithSkipOnArray(): void
    {
        $modelValidator = new FakedValidationModel();
        $validator = Yii::createObject(['class' => TrimValidator::class, 'skipOnArray' => true]);

        $modelValidator->attrA = [
            '  test  ',
            '  value  ',
        ];

        self::assertEmpty(
            $validator->clientValidateAttribute($modelValidator, 'attrA', Yii::$app->view),
            "Should return empty 'string' when 'skipOnArray' is 'true' and 'value' is 'array'.",
        );
        self::assertSame(
            [
                'skipOnArray' => true,
                'skipOnEmpty' => false,
                'chars' => false,
            ],
            $validator->getClientOptions($modelValidator, 'attrA'),
            "Should return correct options 'array'.",
        );

        $validator->validateAttribute($modelValidator, 'attrA');

        self::assertSame(
            [
                '  test  ',
                '  value  ',
            ],
            $modelValidator->attrA,
            "Should skip 'array' values when 'skipOnArray' is 'true'.",
        );
    }
}
