<?php

declare(strict_types=1);

namespace yii\jquery\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\web\client\ClientScriptInterface;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\InputWidget;

/**
 * MaskedInput generates a masked text input.
 *
 * MaskedInput is similar to [[Html::textInput()]] except that an input mask will be used to force users to enter
 * properly formatted data, such as phone numbers, social security numbers.
 *
 * To use MaskedInput, you must set the [[mask]] property. The following example
 * shows how to use MaskedInput to collect phone numbers:
 *
 * ```
 * echo MaskedInput::widget([
 *     'name' => 'phone',
 *     'mask' => '999-999-9999',
 * ]);
 * ```
 *
 * You can also use this widget in an [[ActiveForm]] using the [[ActiveField::widget()|widget()]]
 * method, for example like this:
 *
 * ```
 * <?= $form->field($model, 'from_date')->widget(\yii\jquery\widgets\MaskedInput::class, [
 *     'mask' => '999-999-9999',
 * ]) ?>
 * ```
 *
 * The masked text field is implemented based on the
 * [jQuery input masked plugin](https://github.com/RobinHerbots/Inputmask).
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 0.1
 */
class MaskedInput extends InputWidget
{
    /**
     * The name of the jQuery plugin to use for this widget.
     */
    public const PLUGIN_NAME = 'inputmask';
    /**
     * @var array|null custom aliases to use. Should be configured as `maskAlias => settings`, where
     *
     * - `maskAlias` is a string containing a text to identify your mask alias definition (e.g. 'phone') and
     * - `settings` is an array containing settings for the mask symbol, exactly similar to parameters as passed in [[clientOptions]].
     */
    public array|null $aliases = null;
    /**
     * @var array the JQuery plugin options for the input mask plugin.
     * @see https://github.com/RobinHerbots/Inputmask
     */
    public array $clientOptions = [];

    /**
     * @var array|ClientScriptInterface|string|null The client-side script implementation.
     *
     * When `null` (default), no client script is registered unless configured via the DI container
     * by [[Bootstrap]].
     */
    public array|string|ClientScriptInterface|null $clientScript = null;
    /**
     * @var array|null custom mask definitions to use. Should be configured as `maskSymbol => settings`, where
     *
     * - `maskSymbol` is a string, containing a character to identify your mask definition and
     * - `settings` is an array, consisting of the following entries:
     *   - `validator`: string, a JS regular expression or a JS function.
     *   - `cardinality`: int, specifies how many characters are represented and validated for the definition.
     *   - `prevalidator`: array, validate the characters before the definition cardinality is reached.
     *   - `definitionSymbol`: string, allows shifting values from other definitions, with this `definitionSymbol`.
     */
    public array|null $definitions = null;
    /**
     * @var string|null the hashed variable name used to store the plugin options in the page JS scope.
     * Populated by [[hashPluginOptions()]] before [[registerClientScript()]] delegates to [[clientScript]].
     */
    public string|null $hashVar = null;
    /**
     * @var array|JsExpression|string the input mask (e.g. '99/99/9999' for date input). The following characters
     * can be used in the mask and are predefined:
     *
     * - `a`: represents an alpha character (A-Z, a-z)
     * - `9`: represents a numeric character (0-9)
     * - `*`: represents an alphanumeric character (A-Z, a-z, 0-9)
     * - `[` and `]`: anything entered between the square brackets is considered optional user input. This is
     *   based on the `optionalmarker` setting in [[clientOptions]].
     *
     * Additional definitions can be set through the [[definitions]] property.
     */
    public string|array|JsExpression|null $mask = null;
    /**
     * @var array the HTML attributes for the input tag.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = ['class' => 'form-control'];
    /**
     * @var string the type of the input tag. Currently only 'text' and 'tel' are supported.
     * @see https://github.com/RobinHerbots/Inputmask
     * @since 2.0.6
     */
    public string $type = 'text';
    /**
     * @var string[] the inputmask properties can be contained callbacks
     */
    protected array $jsCallbacks = [
        'oncomplete',
        'onincomplete',
        'oncleared',
        'onKeyDown',
        'onBeforeMask',
        'onBeforePaste',
        'onBeforeWrite',
        'onUnMask',
        'onKeyValidation',
        'isComplete',
    ];

    /**
     * Initializes the widget.
     *
     * @throws InvalidConfigException if the "mask" property is not set.
     */
    public function init(): void
    {
        parent::init();
        if (
            ($this->mask === null || $this->mask === '' || $this->mask === [])
            && (!isset($this->clientOptions['regex']) || $this->clientOptions['regex'] === '')
            && (!isset($this->clientOptions['alias']) || $this->clientOptions['alias'] === '')
        ) {
            throw new InvalidConfigException("Either the 'mask' property, 'clientOptions[\"regex\"]' or the 'clientOptions[\"alias\"]' property must be set.");
        }

        if ($this->clientScript !== null && !$this->clientScript instanceof ClientScriptInterface) {
            $this->clientScript = Yii::createObject($this->clientScript);
        }
    }

    /**
     * Registers the needed client script and options.
     *
     * Prepares plugin options and the hash variable, then delegates to [[clientScript]] when set.
     */
    public function registerClientScript(): void
    {
        $view = $this->getView();
        $this->initClientOptions();
        if ($this->mask !== null && $this->mask !== '' && $this->mask !== []) {
            $this->clientOptions['mask'] = $this->mask;
        }
        $this->hashPluginOptions($view);

        if ($this->clientScript instanceof ClientScriptInterface) {
            $this->clientScript->register($this, $view);
        }
    }

    public function run(): void
    {
        $this->registerClientScript();
        echo $this->renderInputHtml($this->type);
    }

    /**
     * Generates a hashed variable to store the plugin `clientOptions`.
     *
     * Helps in reusing the variable for similar
     * options passed for other widgets on the same page. The following special data attribute will also be
     * added to the input field to allow accessing the client options via javascript:
     *
     * - 'data-plugin-inputmask' will store the hashed variable storing the plugin options.
     *
     * @param View $view the view instance
     * @author [Thiago Talma](https://github.com/thiagotalma)
     */
    protected function hashPluginOptions($view)
    {
        $encOptions = $this->clientOptions === [] ? '{}' : Json::htmlEncode($this->clientOptions);
        $this->hashVar = self::PLUGIN_NAME . '_' . hash('crc32', $encOptions);
        $this->options['data-plugin-' . self::PLUGIN_NAME] = $this->hashVar;
        $view->registerJs("var {$this->hashVar} = {$encOptions};", View::POS_HEAD);
    }

    /**
     * Initializes client options.
     */
    protected function initClientOptions()
    {
        $options = $this->clientOptions;
        foreach ($options as $key => $value) {
            if (
                $value !== null && $value !== ''
                && !$value instanceof JsExpression
                && in_array($key, $this->jsCallbacks, true)
            ) {
                $options[$key] = new JsExpression($value);
            }
        }
        $this->clientOptions = $options;
    }
}
