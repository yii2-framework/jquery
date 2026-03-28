<?php


declare(strict_types=1);

namespace yii\jquery\tests\widgets;

use Yii;
use yii\base\ExitException;
use yii\data\ArrayDataProvider;
use yii\jquery\tests\TestCase;
use yii\jquery\widgets\Pjax;
use yii\web\HeadersAlreadySentException;
use yii\widgets\ListView;

/**
 * Unit tests for {@see Pjax} widget.
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
class PjaxTest extends TestCase
{
    public function testGeneratedIdByPjaxWidget(): void
    {
        ListView::$counter = 0;
        Pjax::$counter = 0;

        $nonPjaxWidget1 = new ListView(['dataProvider' => new ArrayDataProvider()]);

        ob_start();

        $pjax1 = Yii::createObject(['class' => Pjax::class]);

        ob_end_clean();

        $nonPjaxWidget2 = new ListView(['dataProvider' => new ArrayDataProvider()]);

        ob_start();

        $pjax2 = Yii::createObject(['class' => Pjax::class]);

        ob_end_clean();

        self::assertEquals(
            'w0',
            $nonPjaxWidget1->options['id'],
            "Should assign 'w0' to first non-pjax widget.",
        );
        self::assertEquals(
            'w1',
            $nonPjaxWidget2->options['id'],
            "Should assign 'w1' to second non-pjax widget.",
        );
        self::assertEquals(
            'p0',
            $pjax1->options['id'],
            "Should assign 'p0' to first pjax widget.",
        );
        self::assertEquals(
            'p1',
            $pjax2->options['id'],
            "Should assign 'p1' to second pjax widget.",
        );
    }

    public function testInitWithPjaxRequestPathAndTitle(): void
    {
        $obLevel = ob_get_level();

        $_SERVER['HTTP_X_PJAX'] = 'true';
        $_SERVER['HTTP_X_PJAX_CONTAINER'] = '#test-pjax';

        Yii::$app->view->title = 'Test Title';

        try {
            Pjax::begin(['options' => ['id' => 'test-pjax']]);
            Pjax::end();
        } catch (ExitException|HeadersAlreadySentException) {
            // Expected.
        } finally {
            while (ob_get_level() < $obLevel) {
                ob_start();
            }
        }

        self::expectNotToPerformAssertions();

        unset($_SERVER['HTTP_X_PJAX'], $_SERVER['HTTP_X_PJAX_CONTAINER']);
    }

    public function testRegisterClientScriptRegistersJs(): void
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
            'Should register pjax handler with container selector.',
        );
    }

    public function testRequiresPjaxReturnsFalseWithDifferentContainer(): void
    {
        $_SERVER['HTTP_X_PJAX'] = 'true';
        $_SERVER['HTTP_X_PJAX_CONTAINER'] = '#other-pjax';

        ob_start();

        $pjax = Yii::createObject(['class' => Pjax::class, 'options' => ['id' => 'test-pjax']]);

        ob_end_clean();

        $result = $this->invokeMethod($pjax, 'requiresPjax');

        self::assertFalse(
            $result,
            "Should return 'false' when container does not match.",
        );

        unset($_SERVER['HTTP_X_PJAX'], $_SERVER['HTTP_X_PJAX_CONTAINER']);
    }

    public function testRequiresPjaxReturnsFalseWithoutHeaders(): void
    {
        ob_start();

        $pjax = Yii::createObject(['class' => Pjax::class, 'options' => ['id' => 'test-pjax']]);

        ob_end_clean();

        $result = $this->invokeMethod($pjax, 'requiresPjax');

        self::assertFalse(
            $result,
            "Should return 'false' without X-Pjax headers.",
        );
    }

    public function testRequiresPjaxReturnsTrueWithMatchingHeaders(): void
    {
        $obLevel = ob_get_level();

        $_SERVER['HTTP_X_PJAX'] = 'true';
        $_SERVER['HTTP_X_PJAX_CONTAINER'] = '#test-pjax';

        try {
            Pjax::begin(['options' => ['id' => 'test-pjax']]);
            Pjax::end();
        } catch (ExitException|HeadersAlreadySentException) {
            // Expected: Pjax::run() calls Yii::$app->end() when serving a pjax request.
        } finally {
            while (ob_get_level() < $obLevel) {
                ob_start();
            }
        }

        $response = Yii::$app->getResponse();

        self::assertSame(
            200,
            $response->statusCode,
            "Should return '200' status code for matching pjax request.",
        );

        unset($_SERVER['HTTP_X_PJAX'], $_SERVER['HTTP_X_PJAX_CONTAINER']);
    }

    public function testRunNonPjaxRequest(): void
    {
        $_SERVER['REQUEST_URI'] = '/test/page';

        ob_start();

        Pjax::begin(['options' => ['id' => 'test-pjax']]);

        echo 'content';

        Pjax::end();

        $output = ob_get_clean();

        self::assertStringContainsString(
            'data-pjax-container',
            $output,
            'Should render pjax container attribute.',
        );
        self::assertStringContainsString(
            '</div>',
            $output,
            "Should render closing 'div' tag.",
        );
    }

    /**
     * @see https://github.com/yiisoft/yii2/issues/15536
     */
    public function testShouldTriggerInitEvent(): void
    {
        $initTriggered = false;

        ob_start();

        $pjax = Yii::createObject(
            [
                'class' => Pjax::class,
                'on init' => function () use (&$initTriggered): void {
                    $initTriggered = true;
                },
            ],
        );

        ob_end_clean();

        self::assertSame(
            true,
            $initTriggered,
            'Should trigger init event during widget initialization.',
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        Pjax::$counter = 0;

        $_SERVER['REQUEST_URI'] = '/test/page';
    }
}
