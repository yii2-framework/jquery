<?php

declare(strict_types=1);

namespace yii\jquery\tests\validators;

use PHPUnit\Framework\Attributes\Group;
use Yii;
use yii\jquery\tests\data\validators\FakedValidationModel;
use yii\jquery\tests\TestCase;
use yii\validators\FilterValidator;

/**
 * Unit tests for {@see FilterValidator} jQuery client-side validation integration.
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
#[Group('jquery')]
#[Group('validators')]
final class FilterValidatorJqueryTest extends TestCase
{
    public function testClientValidateAttributeWithTrimFilter(): void
    {
        $validator = Yii::createObject(['class' => FilterValidator::class, 'filter' => 'trim']);

        $model = FakedValidationModel::createWithAttributes(['attr_one' => 'test']);

        self::assertSame(
            <<<'JS'
            value = yii.validation.trim($form, attribute, [], value);
            JS,
            $validator->clientValidateAttribute($model, 'attr_one', Yii::$app->view),
            "Should return correct 'trim' validation script.",
        );
    }
}
