<?php


declare(strict_types=1);

namespace yii\jquery\tests\widgets;

use PHPUnit\Framework\Attributes\Group;
use Yii;
use yii\jquery\widgets\Pjax;
use yii\jquery\widgets\PjaxJqueryClientScript;

/**
 * Unit tests for {@see PjaxJqueryClientScript} jQuery client-side script.
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
#[Group('jquery')]
#[Group('widgets')]
final class PjaxJqueryClientScriptTest extends \yii\jquery\tests\TestCase
{
    public function testGetClientOptions(): void
    {
        ob_start();

        $pjax = Yii::createObject(['class' => Pjax::class, 'options' => ['id' => 'test-pjax'], 'timeout' => 2000]);

        ob_end_clean();

        $pjax->registerClientScript();

        $options = $pjax->clientScript->getClientOptions($pjax);

        self::assertArrayHasKey(
            'push',
            $options,
            'Should contain push option.',
        );
        self::assertArrayHasKey(
            'timeout',
            $options,
            'Should contain timeout option.',
        );
    }

    public function testGetClientOptionsReturnsWidgetClientOptions(): void
    {
        ob_start();

        $pjax = Yii::createObject(['class' => Pjax::class, 'options' => ['id' => 'test-pjax']]);

        ob_end_clean();

        $pjax->registerClientScript();

        $clientScript = new PjaxJqueryClientScript();

        $options = $clientScript->getClientOptions($pjax);

        self::assertSame(
            $pjax->clientOptions,
            $options,
            'Should return client options.',
        );
    }

    public function testRegisterWithBothSelectorsFalse(): void
    {
        ob_start();

        $pjax = Yii::createObject(
            [
                'class' => Pjax::class,
                'options' => ['id' => 'test-pjax'],
                'linkSelector' => false,
                'formSelector' => false,
            ],
        );

        ob_end_clean();

        $pjax->registerClientScript();

        $jsBefore = Yii::$app->view->js[4] ?? [];

        self::assertEmpty(
            $jsBefore,
            "No JavaScript should be registered when both selectors are 'false'.",
        );
    }

    public function testRegisterWithCustomSelectors(): void
    {
        ob_start();

        $pjax = Yii::createObject(
            [
                'class' => Pjax::class,
                'options' => ['id' => 'test-pjax'],
                'linkSelector' => '#custom-link',
                'formSelector' => '#custom-form',
            ],
        );

        ob_end_clean();

        $pjax->registerClientScript();

        $js = implode('', Yii::$app->view->js[4] ?? []);

        self::assertSame(
            <<<'JS'
            jQuery(document).pjax("#custom-link", {"push":true,"replace":false,"timeout":1000,"scrollTo":false,"container":"#test-pjax"});
            jQuery(document).off("submit", "#custom-form").on("submit", "#custom-form", function (event) {jQuery.pjax.submit(event, {"push":true,"replace":false,"timeout":1000,"scrollTo":false,"container":"#test-pjax"});});
            JS,
            $js,
            "Should contain custom 'link' and 'form' selectors.",
        );
    }

    public function testRegisterWithDefaultSelectors(): void
    {
        ob_start();

        $pjax = Yii::createObject(['class' => Pjax::class, 'options' => ['id' => 'test-pjax']]);

        ob_end_clean();

        $pjax->registerClientScript();

        $js = implode('', Yii::$app->view->js[4] ?? []);

        self::assertSame(
            <<<'JS'
            jQuery(document).pjax("#test-pjax a", {"push":true,"replace":false,"timeout":1000,"scrollTo":false,"container":"#test-pjax"});
            jQuery(document).off("submit", "#test-pjax form[data-pjax]").on("submit", "#test-pjax form[data-pjax]", function (event) {jQuery.pjax.submit(event, {"push":true,"replace":false,"timeout":1000,"scrollTo":false,"container":"#test-pjax"});});
            JS,
            $js,
            "Should register pjax 'link' handler and 'form' submit handler with 'default' selectors.",
        );
    }

    public function testRegisterWithFormSelectorFalse(): void
    {
        ob_start();

        $pjax = Yii::createObject(['class' => Pjax::class, 'options' => ['id' => 'test-pjax'], 'formSelector' => false]);

        ob_end_clean();

        $pjax->registerClientScript();

        $js = implode('', Yii::$app->view->js[4] ?? []);

        self::assertSame(
            'jQuery(document).pjax("#test-pjax a", {"push":true,"replace":false,"timeout":1000,"scrollTo":false,"container":"#test-pjax"});',
            $js,
            "Should register pjax 'link' handler only when 'form' selector is false.",
        );
    }

    public function testRegisterWithLinkSelectorFalse(): void
    {
        ob_start();

        $pjax = Yii::createObject(['class' => Pjax::class, 'options' => ['id' => 'test-pjax'], 'linkSelector' => false]);

        ob_end_clean();

        $pjax->registerClientScript();

        $js = implode('', Yii::$app->view->js[4] ?? []);

        self::assertSame(
            <<<'JS'

            jQuery(document).off("submit", "#test-pjax form[data-pjax]").on("submit", "#test-pjax form[data-pjax]", function (event) {jQuery.pjax.submit(event, {"push":true,"replace":false,"timeout":1000,"scrollTo":false,"container":"#test-pjax"});});
            JS,
            $js,
            "Should register form submit handler only when 'link' selector is false.",
        );
    }
}
