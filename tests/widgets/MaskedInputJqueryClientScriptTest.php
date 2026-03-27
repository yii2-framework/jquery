<?php


declare(strict_types=1);

namespace yii\jquery\tests\widgets;

use PHPUnit\Framework\Attributes\Group;
use Yii;
use yii\jquery\widgets\MaskedInput;
use yii\jquery\widgets\MaskedInputJqueryClientScript;

/**
 * Unit tests for {@see MaskedInputJqueryClientScript} jQuery client-side script.
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
#[Group('jquery')]
#[Group('widgets')]
final class MaskedInputJqueryClientScriptTest extends \yii\jquery\tests\TestCase
{
    public function testGetClientOptions(): void
    {
        $maskedInput = Yii::createObject(['class' => MaskedInput::class, 'name' => 'phone', 'mask' => '999-9999']);

        $maskedInput->registerClientScript();

        $clientScript = new MaskedInputJqueryClientScript();

        $options = $clientScript->getClientOptions($maskedInput);

        self::assertSame(
            $maskedInput->clientOptions,
            $options,
            'Should return client options.',
        );
    }

    public function testRegisterWithDefinitionsAndAliases(): void
    {
        $maskedInput = Yii::createObject(
            [
                'class' => MaskedInput::class,
                'name' => 'custom',
                'mask' => 'a{1,2}',
                'options' => ['id' => 'test-custom'],
                'definitions' => ['a' => ['validator' => '[A-Za-z]']],
                'aliases' => ['myalias' => ['mask' => '999']],
            ],
        );

        $maskedInput->registerClientScript();

        $js = implode('', Yii::$app->view->js[4] ?? []);

        self::assertSame(
            <<<JS
            Inputmask.extendDefinitions({"a":{"validator":"[A-Za-z]"}});Inputmask.extendAliases({"myalias":{"mask":"999"}});jQuery("#test-custom").inputmask(inputmask_7d61b377);
            JS,
            $js,
            'Should register custom definitions, aliases, and inputmask initialization.',
        );
    }

    public function testRegisterWithSimpleMask(): void
    {
        $maskedInput = Yii::createObject(
            [
                'class' => MaskedInput::class,
                'name' => 'phone',
                'mask' => '999-9999',
                'options' => ['id' => 'test-phone'],
            ],
        );

        $maskedInput->registerClientScript();

        $js = implode('', Yii::$app->view->js[4] ?? []);

        self::assertSame(
            <<<JS
            jQuery("#test-phone").inputmask(inputmask_99765a6a);
            JS,
            $js,
            'Should contain jQuery selector and inputmask initialization.',
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockWebApplication();
    }
}
