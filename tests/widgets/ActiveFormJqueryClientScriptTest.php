<?php

declare(strict_types=1);

namespace yii\jquery\tests\widgets;

use PHPUnit\Framework\Attributes\Group;
use Yii;
use yii\base\DynamicModel;
use yii\jquery\tests\TestCase;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

use function ob_get_clean;
use function ob_implicit_flush;
use function ob_start;

/**
 * Unit tests for {@see ActiveFormJqueryClientScript} jQuery client-side script.
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
#[Group('jquery')]
#[Group('widgets')]
final class ActiveFormJqueryClientScriptTest extends TestCase
{
    public function testGetClientOptionsForFieldWithAriaAttributesFalse(): void
    {
        $model = new DynamicModel(['name']);

        $model->addRule(['name'], 'required');

        $view = Yii::$app->view;

        ob_start();
        ob_implicit_flush(false);

        $form = ActiveForm::begin(
            [
                'id' => 'w0',
                'view' => $view,
            ],
        );

        $field = $form->field($model, 'name');

        $field->addAriaAttributes = false;

        echo $field;

        $form::end();

        $expectedForm = ob_get_clean();

        $csrfToken = Yii::$app->request->csrfToken;

        $validate = '"validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Name cannot be blank."}';

        $this->assertEqualsWithoutLE(
            <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <title>Test</title>
                </head>
            <body>

            <form id="w0" action="/" method="post">
            <input type="hidden" name="_csrf" value="$csrfToken"><div class="form-group field-dynamicmodel-name required">
            <label class="control-label" for="dynamicmodel-name">Name</label>
            <input type="text" id="dynamicmodel-name" class="form-control" name="DynamicModel[name]">

            <div class="help-block"></div>
            </div></form>
            <script src="/assets/5a1b552/jquery.js"></script>
            <script src="/assets/5a1b552/yii.js"></script>
            <script src="/assets/5a1b552/yii.validation.js"></script>
            <script src="/assets/5a1b552/yii.activeForm.js"></script>
            <script>document.addEventListener('DOMContentLoaded', function () {
            jQuery('#w0').yiiActiveForm([{"id":"dynamicmodel-name","name":"name","container":".field-dynamicmodel-name","input":"#dynamicmodel-name",$validate);},"updateAriaInvalid":false}], []);
            });</script></body>
            </html>

            HTML,
            $view->render('@tests/data/views/layout.php', ['content' => $expectedForm]),
            'Rendered HTML does not match expected output',
        );
        self::assertFalse(
            $form->clientScript->getClientOptions($field)['updateAriaInvalid'] ?? null,
            "Should return correct options 'array'.",
        );
    }

    public function testGetClientOptionsForFieldWithAttributeNotActive(): void
    {
        $model = new DynamicModel(['name', 'inactive']);

        $model->addRule(['name'], 'required');

        ob_start();
        ob_implicit_flush(false);

        $form = ActiveForm::begin(['id' => 'w0']);

        $field = $form->field($model, 'inactive');
        $form::end();

        ob_get_clean();

        $options = $form->clientScript->getClientOptions($field);

        self::assertSame(
            [],
            $options,
            "Should return empty 'array' when attribute is not in 'activeAttributes'.",
        );
    }

    public function testGetClientOptionsForFieldWithCustomErrorSelector(): void
    {
        $model = new DynamicModel(['name']);

        $model->addRule(['name'], 'required');

        $view = Yii::$app->view;

        ob_start();
        ob_implicit_flush(false);

        $form = ActiveForm::begin(
            [
                'id' => 'w0',
                'view' => $view,
            ],
        );

        $field = $form->field($model, 'name');

        $field->selectors = ['error' => '.custom-error-selector'];

        echo $field;

        $form::end();

        $expectedForm = ob_get_clean();

        $csrfToken = Yii::$app->request->csrfToken;

        $validate = '"validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Name cannot be blank."});}';

        $this->assertEqualsWithoutLE(
            <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <title>Test</title>
                </head>
            <body>

            <form id="w0" action="/" method="post">
            <input type="hidden" name="_csrf" value="$csrfToken"><div class="form-group field-dynamicmodel-name required">
            <label class="control-label" for="dynamicmodel-name">Name</label>
            <input type="text" id="dynamicmodel-name" class="form-control" name="DynamicModel[name]" aria-required="true">

            <div class="help-block"></div>
            </div></form>
            <script src="/assets/5a1b552/jquery.js"></script>
            <script src="/assets/5a1b552/yii.js"></script>
            <script src="/assets/5a1b552/yii.validation.js"></script>
            <script src="/assets/5a1b552/yii.activeForm.js"></script>
            <script>document.addEventListener('DOMContentLoaded', function () {
            jQuery('#w0').yiiActiveForm([{"id":"dynamicmodel-name","name":"name","container":".field-dynamicmodel-name","input":"#dynamicmodel-name","error":".custom-error-selector",$validate}], []);
            });</script></body>
            </html>

            HTML,
            $view->render('@tests/data/views/layout.php', ['content' => $expectedForm]),
            'Rendered HTML does not match expected output',
        );
        self::assertSame(
            '.custom-error-selector',
            $form->clientScript->getClientOptions($field)['error'] ?? null,
            'Should use selectors error when set.',
        );
    }

    public function testGetClientOptionsForFieldWithDefaultTag(): void
    {
        $model = new DynamicModel(['name']);

        $model->addRule(['name'], 'string');

        $view = Yii::$app->view;

        ob_start();
        ob_implicit_flush(false);

        $form = ActiveForm::begin(
            [
                'id' => 'w0',
                'view' => $view,
            ],
        );

        $field = $form->field($model, 'name');

        unset($field->selectors['error']);

        $field->errorOptions = [];

        echo $field;

        $form::end();

        $expectedForm = ob_get_clean();

        $csrfToken = Yii::$app->request->csrfToken;
        $validate = '"validate":function (attribute, value, messages, deferred, $form) {yii.validation.string(value, messages, {"message":"Name must be a string.","skipOnEmpty":1';

        $this->assertEqualsWithoutLE(
            <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <title>Test</title>
                </head>
            <body>

            <form id="w0" action="/" method="post">
            <input type="hidden" name="_csrf" value="$csrfToken"><div class="form-group field-dynamicmodel-name">
            <label class="control-label" for="dynamicmodel-name">Name</label>
            <input type="text" id="dynamicmodel-name" class="form-control" name="DynamicModel[name]">

            <div></div>
            </div></form>
            <script src="/assets/5a1b552/jquery.js"></script>
            <script src="/assets/5a1b552/yii.js"></script>
            <script src="/assets/5a1b552/yii.validation.js"></script>
            <script src="/assets/5a1b552/yii.activeForm.js"></script>
            <script>document.addEventListener('DOMContentLoaded', function () {
            jQuery('#w0').yiiActiveForm([{"id":"dynamicmodel-name","name":"name","container":".field-dynamicmodel-name","input":"#dynamicmodel-name","error":"span",$validate});}}], []);
            });</script></body>
            </html>

            HTML,
            $view->render('@tests/data/views/layout.php', ['content' => $expectedForm]),
            'Rendered HTML does not match expected output',
        );
        self::assertSame(
            'span',
            $form->clientScript->getClientOptions($field)['error'] ?? null,
            "Should use 'errorOptions' tag or 'span' as fallback.",
        );
    }

    public function testGetClientOptionsForFieldWithErrorOptionsClass(): void
    {
        $model = new DynamicModel(['name']);

        $model->addRule(['name'], 'required');

        $view = Yii::$app->view;

        ob_start();
        ob_implicit_flush(false);

        $form = ActiveForm::begin(
            [
                'id' => 'w0',
                'view' => $view,
            ],
        );

        $field = $form->field($model, 'name');

        unset($field->selectors['error']);

        $field->errorOptions = ['class' => 'error-class another-class'];

        echo $field;

        $form::end();

        $expectedForm = ob_get_clean();

        $csrfToken = Yii::$app->request->csrfToken;
        $validate = '"validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Name cannot be blank."});}';

        $this->assertEqualsWithoutLE(
            <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <title>Test</title>
                </head>
            <body>

            <form id="w0" action="/" method="post">
            <input type="hidden" name="_csrf" value="$csrfToken"><div class="form-group field-dynamicmodel-name required">
            <label class="control-label" for="dynamicmodel-name">Name</label>
            <input type="text" id="dynamicmodel-name" class="form-control" name="DynamicModel[name]" aria-required="true">

            <div class="error-class another-class"></div>
            </div></form>
            <script src="/assets/5a1b552/jquery.js"></script>
            <script src="/assets/5a1b552/yii.js"></script>
            <script src="/assets/5a1b552/yii.validation.js"></script>
            <script src="/assets/5a1b552/yii.activeForm.js"></script>
            <script>document.addEventListener('DOMContentLoaded', function () {
            jQuery('#w0').yiiActiveForm([{"id":"dynamicmodel-name","name":"name","container":".field-dynamicmodel-name","input":"#dynamicmodel-name","error":".error-class.another-class",$validate}], []);
            });</script></body>
            </html>

            HTML,
            $view->render('@tests/data/views/layout.php', ['content' => $expectedForm]),
            'Rendered HTML does not match expected output',
        );
    }

    public function testGetClientOptionsForFieldWithFieldLevelAjaxValidation(): void
    {
        $model = new DynamicModel(['name']);

        $model->addRule(['name'], 'required');

        ob_start();
        ob_implicit_flush(false);

        $form = ActiveForm::begin(['id' => 'w0', 'enableAjaxValidation' => false]);

        $field = $form->field($model, 'name');

        $field->enableAjaxValidation = true;

        echo $field;

        $form::end();

        ob_get_clean();

        $options = $form->clientScript->getClientOptions($field);

        self::assertTrue(
            $options['enableAjaxValidation'] ?? false,
            "Should set 'enableAjaxValidation' when field-level ajax validation is 'true'.",
        );
    }

    public function testGetClientOptionsForFieldWithFieldLevelClientValidation(): void
    {
        $model = new DynamicModel(['name']);

        $model->addRule(['name'], 'required');

        ob_start();
        ob_implicit_flush(false);

        $form = ActiveForm::begin(['id' => 'w0']);

        $field = $form->field($model, 'name');

        $field->enableClientValidation = false;

        echo $field;

        $form::end();

        ob_get_clean();

        $options = $form->clientScript->getClientOptions($field);

        self::assertSame(
            [],
            $options,
            "Should return empty 'array' when field-level 'enableClientValidation' is 'false'.",
        );
    }

    public function testGetClientOptionsForFieldWithWhenClient(): void
    {
        $model = new DynamicModel(['name']);

        $model->addRule(
            ['name'],
            'required',
            ['whenClient' => 'function(attribute, value) { return true; }'],
        );

        ob_start();
        ob_implicit_flush(false);

        $form = ActiveForm::begin(['id' => 'w0']);

        $field = $form->field($model, 'name');

        echo $field;

        $form::end();

        ob_get_clean();

        $options = $form->clientScript->getClientOptions($field);

        self::assertArrayHasKey(
            'validate',
            $options,
            'Should contain validate key.',
        );

        $expression = $options['validate'] instanceof JsExpression
            ? (string) $options['validate']
            : '';

        self::assertSame(
            <<<'JS'
            function (attribute, value, messages, deferred, $form) {if ((function(attribute, value) { return true; })(attribute, value)) { yii.validation.required(value, messages, {"message":"Name cannot be blank."}); }}
            JS,
            $expression,
            "Should wrap validation JS in an if-statement with 'whenClient'.",
        );
    }

    public function testRegisterClientScript(): void
    {
        $model = new DynamicModel(['name']);

        $model->addRule(['name'], 'required');

        $view = Yii::$app->view;

        ob_start();
        ob_implicit_flush(false);

        $form = ActiveForm::begin(
            [
                'id' => 'w0',
                'view' => $view,
                'validateOnSubmit' => false,
                'validationUrl' => '/custom/validation',
            ],
        );

        echo $form->field($model, 'name');

        $form::end();

        $expectedForm = ob_get_clean();

        $csrfToken = Yii::$app->request->csrfToken;

        $validate = '"validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Name cannot be blank."});}';

        $this->assertEqualsWithoutLE(
            <<<HTML
            <!DOCTYPE html>
            <html>
            <head>
                <title>Test</title>
                </head>
            <body>

            <form id="w0" action="/" method="post">
            <input type="hidden" name="_csrf" value="{$csrfToken}"><div class="form-group field-dynamicmodel-name required">
            <label class="control-label" for="dynamicmodel-name">Name</label>
            <input type="text" id="dynamicmodel-name" class="form-control" name="DynamicModel[name]" aria-required="true">

            <div class="help-block"></div>
            </div></form>
            <script src="/assets/5a1b552/jquery.js"></script>
            <script src="/assets/5a1b552/yii.js"></script>
            <script src="/assets/5a1b552/yii.validation.js"></script>
            <script src="/assets/5a1b552/yii.activeForm.js"></script>
            <script>document.addEventListener('DOMContentLoaded', function () {
            jQuery('#w0').yiiActiveForm([{"id":"dynamicmodel-name","name":"name","container":".field-dynamicmodel-name","input":"#dynamicmodel-name",$validate}], {"validateOnSubmit":false,"validationUrl":"\/custom\/validation"});
            });</script></body>
            </html>

            HTML,
            $view->render('@tests/data/views/layout.php', ['content' => $expectedForm]),
            'Rendered HTML does not match expected output',
        );
        self::assertSame(
            [
                'validateOnSubmit' => false,
                'validationUrl' => '/custom/validation',
            ],
            $this->invokeMethod($form, 'getClientOptions'),
            "Should return correct options 'array'.",
        );
        self::assertSame(
            [
                'validateOnSubmit' => false,
                'validationUrl' => '/custom/validation',
            ],
            $form->clientScript->getClientOptions($form),
            "Should return correct options 'array'.",
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $_SERVER['REQUEST_URI'] = 'https://example.com/';
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $_SERVER = [];
    }
}
