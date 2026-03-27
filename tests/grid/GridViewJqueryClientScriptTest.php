<?php

declare(strict_types=1);

namespace yii\jquery\tests\grid;

use PHPUnit\Framework\Attributes\Group;
use Yii;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;

/**
 * Unit tests for {@see GridViewJqueryClientScript} jQuery client-side script.
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
#[Group('jquery')]
final class GridViewJqueryClientScriptTest extends \yii\jquery\tests\TestCase
{
    public function testRegister(): void
    {
        $config = [
            'id' => 'test-grid',
            'dataProvider' => new ArrayDataProvider(['allModels' => []]),
            'filterUrl' => '/test/filter',
            'filterSelector' => '#custom-filter input',
            'options' => ['id' => 'test-grid'],
            'filterRowOptions' => ['id' => 'test-grid-filters'],
        ];

        $this->assertEqualsWithoutLE(
            <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <title>Test</title>
                </head>
            <body>

            <div id="test-grid">
            <table class="table table-striped table-bordered"><thead>
            <tr></tr>
            </thead>
            <tbody>
            <tr><td colspan="0"><div class="empty">No results found.</div></td></tr>
            </tbody></table>
            </div>
            <script src="/assets/5a1b552/jquery.js"></script>
            <script src="/assets/5a1b552/yii.js"></script>
            <script src="/assets/5a1b552/yii.gridView.js"></script>
            <script>document.addEventListener('DOMContentLoaded', function () {
            jQuery('#test-grid').yiiGridView({"filterUrl":"\/test\/filter","filterSelector":"#test-grid-filters input, #test-grid-filters select, #custom-filter input","filterOnFocusOut":true});
            });</script></body>
            </html>

            HTML,
            Yii::$app->view->render('@tests/data/views/layout.php', ['content' => GridView::widget($config)]),
            'Rendered HTML does not match expected output',
        );

        $gridView = Yii::createObject(['class' => GridView::class, ...$config]);

        self::assertSame(
            [
                'filterUrl' => '/test/filter',
                'filterSelector' => '#test-grid-filters input, #test-grid-filters select, #custom-filter input',
            ],
            $gridView->clientScript->getClientOptions($gridView),
            "Should return correct options 'array'.",
        );
        self::assertSame(
            [
                'filterUrl' => '/test/filter',
                'filterSelector' => '#test-grid-filters input, #test-grid-filters select, #custom-filter input',
            ],
            $this->invokeMethod($gridView, 'getClientOptions'),
            "Should return correct options 'array'.",
        );
    }

    public function testRegisterWithClosureFilterSelector(): void
    {
        $config = [
            'id' => 'test-grid',
            'dataProvider' => new ArrayDataProvider(['allModels' => []]),
            'filterUrl' => '/test/filter',
            'filterSelector' => static fn(string $id, string $filterId): string => '#extra-filter input',
            'options' => ['id' => 'test-grid'],
            'filterRowOptions' => ['id' => 'test-grid-filters'],
        ];

        $gridView = Yii::createObject(['class' => GridView::class, ...$config]);

        $options = $gridView->clientScript->getClientOptions($gridView);

        self::assertSame(
            '#test-grid-filters input, #test-grid-filters select, #extra-filter input',
            $options['filterSelector'],
            'Closure filterSelector should be resolved and appended.',
        );
    }

    public function testRegisterWithComplexFilterUrl(): void
    {
        $config = [
            'id' => 'test-grid',
            'dataProvider' => new ArrayDataProvider(['allModels' => []]),
            'filterUrl' => '/test/filter?param=value&other=123',
            'options' => ['id' => 'test-grid'],
            'filterRowOptions' => ['id' => 'test-grid-filters'],
        ];

        $this->assertEqualsWithoutLE(
            <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <title>Test</title>
                </head>
            <body>

            <div id="test-grid">
            <table class="table table-striped table-bordered"><thead>
            <tr></tr>
            </thead>
            <tbody>
            <tr><td colspan="0"><div class="empty">No results found.</div></td></tr>
            </tbody></table>
            </div>
            <script src="/assets/5a1b552/jquery.js"></script>
            <script src="/assets/5a1b552/yii.js"></script>
            <script src="/assets/5a1b552/yii.gridView.js"></script>
            <script>document.addEventListener('DOMContentLoaded', function () {
            jQuery('#test-grid').yiiGridView({"filterUrl":"\/test\/filter?param=value\u0026other=123","filterSelector":"#test-grid-filters input, #test-grid-filters select","filterOnFocusOut":true});
            });</script></body>
            </html>

            HTML,
            Yii::$app->view->render('@tests/data/views/layout.php', ['content' => GridView::widget($config)]),
            'Rendered HTML does not match expected output',
        );

        $gridView = Yii::createObject(['class' => GridView::class, ...$config]);

        self::assertSame(
            [
                'filterUrl' => '/test/filter?param=value&other=123',
                'filterSelector' => '#test-grid-filters input, #test-grid-filters select',
            ],
            $gridView->clientScript->getClientOptions($gridView),
            "Should return correct options 'array'.",
        );
    }

    public function testRegisterWithCustomFilterSelector(): void
    {
        $config = [
            'id' => 'test-grid',
            'dataProvider' => new ArrayDataProvider(['allModels' => []]),
            'filterUrl' => '/test/filter',
            'options' => ['id' => 'test-grid'],
            'filterRowOptions' => ['id' => 'test-grid-filters'],
        ];

        $this->assertEqualsWithoutLE(
            <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <title>Test</title>
                </head>
            <body>

            <div id="test-grid">
            <table class="table table-striped table-bordered"><thead>
            <tr></tr>
            </thead>
            <tbody>
            <tr><td colspan="0"><div class="empty">No results found.</div></td></tr>
            </tbody></table>
            </div>
            <script src="/assets/5a1b552/jquery.js"></script>
            <script src="/assets/5a1b552/yii.js"></script>
            <script src="/assets/5a1b552/yii.gridView.js"></script>
            <script>document.addEventListener('DOMContentLoaded', function () {
            jQuery('#test-grid').yiiGridView({"filterUrl":"\/test\/filter","filterSelector":"#test-grid-filters input, #test-grid-filters select","filterOnFocusOut":true});
            });</script></body>
            </html>

            HTML,
            Yii::$app->view->render('@tests/data/views/layout.php', ['content' => GridView::widget($config)]),
            'Rendered HTML does not match expected output',
        );

        $gridView = Yii::createObject(['class' => GridView::class, ...$config]);

        self::assertSame(
            [
                'filterUrl' => '/test/filter',
                'filterSelector' => '#test-grid-filters input, #test-grid-filters select',
            ],
            $gridView->clientScript->getClientOptions($gridView),
            "Should return correct options 'array'.",
        );
    }

    public function testRegisterWithDefaultFilterUrl(): void
    {
        Yii::$app->request->setUrl('/default/url');

        $config = [
            'id' => 'test-grid',
            'dataProvider' => new ArrayDataProvider(['allModels' => []]),
            'options' => ['id' => 'test-grid'],
            'filterRowOptions' => ['id' => 'test-grid-filters'],
        ];

        $this->assertEqualsWithoutLE(
            <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <title>Test</title>
                </head>
            <body>

            <div id="test-grid">
            <table class="table table-striped table-bordered"><thead>
            <tr></tr>
            </thead>
            <tbody>
            <tr><td colspan="0"><div class="empty">No results found.</div></td></tr>
            </tbody></table>
            </div>
            <script src="/assets/5a1b552/jquery.js"></script>
            <script src="/assets/5a1b552/yii.js"></script>
            <script src="/assets/5a1b552/yii.gridView.js"></script>
            <script>document.addEventListener('DOMContentLoaded', function () {
            jQuery('#test-grid').yiiGridView({"filterUrl":"\/default\/url","filterSelector":"#test-grid-filters input, #test-grid-filters select","filterOnFocusOut":true});
            });</script></body>
            </html>

            HTML,
            Yii::$app->view->render('@tests/data/views/layout.php', ['content' => GridView::widget($config)]),
            'Rendered HTML does not match expected output',
        );

        $gridView = Yii::createObject(['class' => GridView::class, ...$config]);

        self::assertSame(
            [
                'filterUrl' => '/default/url',
                'filterSelector' => '#test-grid-filters input, #test-grid-filters select',
            ],
            $gridView->clientScript->getClientOptions($gridView),
            "Should return correct options 'array'.",
        );
    }

    public function testRegisterWithOverrideFilterSelector(): void
    {
        $config = [
            'id' => 'test-grid',
            'dataProvider' => new ArrayDataProvider(['allModels' => []]),
            'filterUrl' => '/test/filter',
            'filterSelector' => '#override-filter input',
            'overrideFilterSelector' => true,
            'options' => ['id' => 'test-grid'],
            'filterRowOptions' => ['id' => 'test-grid-filters'],
        ];

        $gridView = Yii::createObject(['class' => GridView::class, ...$config]);

        self::assertSame(
            [
                'filterUrl' => '/test/filter',
                'filterSelector' => '#override-filter input',
            ],
            $gridView->clientScript->getClientOptions($gridView),
            "Should replace default selector with 'overrideFilterSelector'.",
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $_SERVER['REQUEST_URI'] = 'https://example.com/';
    }
}
