<?php

declare(strict_types=1);

namespace yii\jquery\tests\validators;

use Yii;
use yii\jquery\tests\data\validators\FakedValidationModel;
use yii\jquery\tests\TestCase;
use yii\validators\FilterValidator;
use PHPUnit\Framework\Attributes\Group;

/**
 * Unit tests for {@see FilterValidator} jQuery client-side validation integration.
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
#[Group('validators')]
final class FilterValidatorJqueryTest extends TestCase
{
    public function testClientValidateAttributeWithTrimFilter(): void
    {
        $val = Yii::createObject(['class' => FilterValidator::class, 'filter' => 'trim']);

        $m = FakedValidationModel::createWithAttributes(['attr_one' => 'test']);

        $js = $val->clientValidateAttribute($m, 'attr_one', Yii::$app->view);

        self::assertSame(
            <<<'JS'
            value = yii.validation.trim($form, attribute, [], value);
            JS,
            $js,
            "Should return correct 'trim' validation script.",
        );
    }
}
