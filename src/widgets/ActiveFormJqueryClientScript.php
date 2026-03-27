<?php

declare(strict_types=1);

namespace yii\jquery\widgets;

use yii\base\BaseObject;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\client\ClientScriptInterface;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

use function array_diff_assoc;
use function implode;
use function in_array;
use function is_string;
use function preg_split;

/**
 * jQuery client-side script for [[ActiveForm]] and [[ActiveField]].
 *
 * Registers the `yii.activeForm` jQuery plugin and encodes form/field validation options.
 *
 * @implements ClientScriptInterface<ActiveForm|ActiveField>
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
class ActiveFormJqueryClientScript extends BaseObject implements ClientScriptInterface
{
    public function getClientOptions(BaseObject $widget, array $params = []): array
    {
        if ($widget instanceof ActiveForm) {
            return $this->getClientOptionsInternal($widget);
        }

        return $this->getClientOptionsForFieldInternal($widget, $params);
    }

    public function getClientOptionsForFieldInternal(ActiveField $field, array $options = []): array
    {
        $attribute = Html::getAttributeName($field->attribute);

        if (!in_array($attribute, $field->model->activeAttributes(), true)) {
            return [];
        }

        $clientValidation = $this->isClientValidationEnabled($field);
        $ajaxValidation = $this->isAjaxValidationEnabled($field);

        $validators = [];

        if ($clientValidation) {
            foreach ($field->model->getActiveValidators($attribute) as $validator) {
                $js = $validator->clientValidateAttribute($field->model, $attribute, $field->form->getView());

                if ($validator->enableClientValidation && $js !== '') {
                    if ($validator->whenClient !== null) {
                        $js = "if (({$validator->whenClient})(attribute, value)) { $js }";
                    }

                    $validators[] = $js;
                }
            }
        }

        if (!$ajaxValidation && (!$clientValidation || $validators === [])) {
            return [];
        }

        $inputID = $options['id'] ?? Html::getInputId($field->model, $field->attribute);

        $container = is_string($inputID) && $inputID !== '' ? ".field-$inputID" : null;
        $input = is_string($inputID) && $inputID !== '' ? "#$inputID" : null;

        $options['id'] = $inputID;
        $options['name'] = $field->attribute;
        $options['container'] = $field->selectors['container'] ?? $container;
        $options['input'] = $field->selectors['input'] ?? $input;

        if (isset($field->selectors['error'])) {
            $options['error'] = $field->selectors['error'];
        } elseif (isset($field->errorOptions['class'])) {
            $options['error'] = '.' . implode(
                '.',
                preg_split('/\s+/', $field->errorOptions['class'], -1, PREG_SPLIT_NO_EMPTY),
            );
        } else {
            $options['error'] = $field->errorOptions['tag'] ?? 'span';
        }

        $options['encodeError'] = !isset($field->errorOptions['encode']) || $field->errorOptions['encode'];

        if ($ajaxValidation) {
            $options['enableAjaxValidation'] = true;
        }

        foreach (['validateOnChange', 'validateOnBlur', 'validateOnType', 'validationDelay'] as $name) {
            $options[$name] = $field->$name ?? $field->form->$name;
        }

        if ($validators !== []) {
            $options['validate'] = new JsExpression(
                'function (attribute, value, messages, deferred, $form) {' . implode('', $validators) . '}',
            );
        }

        if ($field->addAriaAttributes === false) {
            $options['updateAriaInvalid'] = false;
        }

        // only keep options that differ from the defaults (set in yii.activeForm.js)
        $defaults = [
            'validateOnChange' => true,
            'validateOnBlur' => true,
            'validateOnType' => false,
            'validationDelay' => 500,
            'encodeError' => true,
            'error' => '.help-block',
            'updateAriaInvalid' => true,
        ];

        return array_filter(
            $options,
            static fn(mixed $value, string $key): bool => !array_key_exists($key, $defaults) || $defaults[$key] !== $value,
            ARRAY_FILTER_USE_BOTH,
        );
    }

    public function register(BaseObject $widget, View $view, array $params = []): void
    {
        $id = $widget->options['id'];

        $options = Json::htmlEncode($this->getClientOptions($widget));
        $attributes = Json::htmlEncode($widget->attributes);

        ActiveFormAsset::register($view);

        if (is_string($id) && $id !== '') {
            $view->registerJs("jQuery('#$id').yiiActiveForm($attributes, $options);");
        }
    }

    private function getClientOptionsInternal(ActiveForm $form): array
    {
        $options = [
            'encodeErrorSummary' => $form->encodeErrorSummary,
            'errorSummary' => '.' . implode(
                '.',
                preg_split('/\s+/', $form->errorSummaryCssClass, -1, PREG_SPLIT_NO_EMPTY),
            ),
            'validateOnSubmit' => $form->validateOnSubmit,
            'errorCssClass' => $form->errorCssClass,
            'successCssClass' => $form->successCssClass,
            'validatingCssClass' => $form->validatingCssClass,
            'ajaxParam' => $form->ajaxParam,
            'ajaxDataType' => $form->ajaxDataType,
            'scrollToError' => $form->scrollToError,
            'scrollToErrorOffset' => $form->scrollToErrorOffset,
            'validationStateOn' => $form->validationStateOn,
        ];

        if ($form->validationUrl !== null) {
            $options['validationUrl'] = Url::to($form->validationUrl);
        }

        // only get the options that are different from the default ones (set in yii.activeForm.js)
        return array_diff_assoc(
            $options,
            [
                'encodeErrorSummary' => true,
                'errorSummary' => '.error-summary',
                'validateOnSubmit' => true,
                'errorCssClass' => 'has-error',
                'successCssClass' => 'has-success',
                'validatingCssClass' => 'validating',
                'ajaxParam' => 'ajax',
                'ajaxDataType' => 'json',
                'scrollToError' => true,
                'scrollToErrorOffset' => 0,
                'validationStateOn' => ActiveForm::VALIDATION_STATE_ON_CONTAINER,
            ],
        );
    }

    private function isAjaxValidationEnabled(ActiveField $field): bool
    {
        if ($field->enableAjaxValidation !== null) {
            return $field->enableAjaxValidation;
        }

        return $field->form->enableAjaxValidation;
    }

    private function isClientValidationEnabled(ActiveField $field): bool
    {
        if ($field->enableClientValidation !== null) {
            return $field->enableClientValidation;
        }

        return $field->form->enableClientValidation;
    }
}
