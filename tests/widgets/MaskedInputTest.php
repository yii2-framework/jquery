<?php

declare(strict_types=1);

namespace yii\jquery\tests\widgets;

use PHPUnit\Framework\Attributes\Group;
use Yii;
use yii\base\InvalidConfigException;
use yii\jquery\tests\TestCase;
use yii\jquery\widgets\MaskedInput;
use yii\web\JsExpression;

/**
 * Unit tests for {@see MaskedInput} widget.
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
#[Group('widgets')]
final class MaskedInputTest extends TestCase
{
    public function testInitThrowsExceptionWithoutMaskOrAlias(): void
    {
        $this->expectException(InvalidConfigException::class);
        $this->expectExceptionMessage("Either the 'mask' property");

        MaskedInput::widget(['name' => 'phone']);
    }

    public function testRunWithClientOptions(): void
    {
        $output = MaskedInput::widget(
            [
                'id' => 'w0',
                'name' => 'phone',
                'mask' => '999-9999',
                'clientOptions' => ['placeholder' => '_'],
            ],
        );

        self::assertSame(
            <<<HTML
            <input type="text" id="w0" class="form-control" name="phone" data-plugin-inputmask="inputmask_46206048">
            HTML,
            $output,
            'Should render text input.',
        );

        $headJs = implode('', Yii::$app->view->js[1] ?? []);

        self::assertSame(
            <<<JS
            var inputmask_46206048 = {"placeholder":"_","mask":"999-9999"};
            JS,
            $headJs,
            'Should contain placeholder option.',
        );
    }

    public function testRunWithJsCallbackInClientOptions(): void
    {
        $output = MaskedInput::widget(
            [
                'id' => 'w0',
                'name' => 'phone',
                'mask' => '999-9999',
                'clientOptions' => [
                    'oncomplete' => 'function() { alert("complete"); }',
                ],
            ],
        );

        self::assertSame(
            <<<HTML
            <input type="text" id="w0" class="form-control" name="phone" data-plugin-inputmask="inputmask_42535d2f">
            HTML,
            $output,
            'Should render text input.',
        );

        $headJs = implode('', Yii::$app->view->js[1] ?? []);

        self::assertSame(
            <<<JS
            var inputmask_42535d2f = {"oncomplete":function() { alert("complete"); },"mask":"999-9999"};
            JS,
            $headJs,
            'Should contain oncomplete callback.',
        );
    }

    public function testRunWithJsExpressionCallback(): void
    {
        $output = MaskedInput::widget(
            [
                'id' => 'w0',
                'name' => 'phone',
                'mask' => '999-9999',
                'clientOptions' => ['oncomplete' => new JsExpression('function() { alert("complete"); }')],
            ],
        );

        self::assertSame(
            <<<HTML
            <input type="text" id="w0" class="form-control" name="phone" data-plugin-inputmask="inputmask_42535d2f">
            HTML,
            $output,
            'Should render text input.',
        );

        $headJs = implode('', Yii::$app->view->js[1] ?? []);

        self::assertSame(
            <<<JS
            var inputmask_42535d2f = {"oncomplete":function() { alert("complete"); },"mask":"999-9999"};
            JS,
            $headJs,
            'Should contain oncomplete expression.',
        );
    }

    public function testRunWithSimpleMask(): void
    {
        $output = MaskedInput::widget(
            [
                'id' => 'w0',
                'name' => 'phone',
                'mask' => '999-9999',
                'options' => ['id' => 'test-simple'],
            ],
        );

        self::assertSame(
            <<<HTML
            <input type="text" id="test-simple" name="phone" data-plugin-inputmask="inputmask_99765a6a">
            HTML,
            $output,
            'Should render text input.',
        );

        $js = implode('', Yii::$app->view->js[4] ?? []);

        self::assertSame(
            <<<JS
            jQuery("#test-simple").inputmask(inputmask_99765a6a);
            JS,
            $js,
            'Should contain inputmask initialization.',
        );
    }
}
