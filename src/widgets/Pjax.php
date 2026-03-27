<?php

declare(strict_types=1);

namespace yii\jquery\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\client\ClientScriptInterface;
use yii\web\Response;

use function is_string;

/**
 * Pjax is a widget integrating the [pjax](https://github.com/yiisoft/jquery-pjax) jQuery plugin.
 *
 * Pjax only deals with the content enclosed between its [[begin()]] and [[end()]] calls, called the *body content* of
 * the widget.
 * By default, any link click or form submission (for those forms with `data-pjax` attribute) within the body content
 * will trigger an AJAX request. In responding to the AJAX request, Pjax will send the updated body content (based
 * on the AJAX request) to the client which will replace the old content with the new one. The browser's URL will then
 * be updated using pushState. The whole process requires no reloading of the layout or resources (js, css).
 *
 * You may configure [[linkSelector]] to specify which links should trigger pjax, and configure [[formSelector]] to
 * specify which form submission may trigger pjax.
 *
 * You may disable pjax for a specific link inside the container by adding `data-pjax="0"` attribute to this link.
 *
 * The following example shows how to use Pjax with the [[\yii\grid\GridView]] widget so that the grid pagination,
 * sorting and filtering can be done via pjax:
 *
 * ```
 * use yii\jquery\widgets\Pjax;
 *
 * Pjax::begin();
 * echo GridView::widget([...]);
 * Pjax::end();
 * ```
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 0.1
 */
class Pjax extends Widget
{
    public static $autoIdPrefix = 'p';
    /**
     * @var array Additional options to be passed to the pjax JS plugin. Please refer to the
     * [pjax project page](https://github.com/yiisoft/jquery-pjax) for available options.
     */
    public array $clientOptions = [];
    /**
     * @var array|string|ClientScriptInterface|null The client-side script implementation.
     *
     * When `null` (default), no client script is registered unless configured via the DI container
     * by [[Bootstrap]].
     */
    public array|string|ClientScriptInterface|null $clientScript = null;
    /**
     * @internal
     */
    public static $counter = 0;
    /**
     * @var bool Whether to enable push state.
     */
    public bool $enablePushState = true;
    /**
     * @var bool Whether to enable replace state.
     */
    public bool $enableReplaceState = false;
    /**
     * @var string|false|null The jQuery selector of the forms whose submissions should trigger pjax requests.
     *
     * If not set, all forms with `data-pjax` attribute within the enclosed content of Pjax will trigger pjax requests.
     * If set to false, no code will be registered to handle forms.
     * Note that if the response to the pjax request is a full page, a normal request will be sent again.
     */
    public string|false|null $formSelector = null;
    /**
     * @var string|false|null The jQuery selector of the links that should trigger pjax requests.
     *
     * If not set, all links within the enclosed content of Pjax will trigger pjax requests.
     * If set to false, no code will be registered to handle links.
     * Note that if the response to the pjax request is a full page, a normal request will be sent again.
     */
    public string|false|null $linkSelector = null;
    /**
     * @var array The HTML attributes for the widget container tag. The following special options are recognized:
     *
     * - `tag`: string, the tag name for the container. Defaults to `div`
     *   This option is available since version 2.0.7.
     *   See also [[\yii\helpers\Html::tag()]].
     *
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $options = [];
    /**
     * @var bool|int How to scroll the page when pjax response is received. If false, no page scroll will be made.
     *
     * Use a number if you want to scroll to a particular place.
     */
    public bool|int $scrollTo = false;
    /**
     * @var string The jQuery event that will trigger form handler. Defaults to "submit".
     *
     * @since 2.0.9
     */
    public string $submitEvent = 'submit';
    /**
     * @var int Pjax timeout setting (in milliseconds). This timeout is used when making AJAX requests.
     *
     * Use a bigger number if your server is slow. If the server does not respond within the timeout, a full page load
     * will be triggered.
     */
    public int $timeout = 1000;


    public function init()
    {
        parent::init();

        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        if ($this->clientScript !== null && !$this->clientScript instanceof ClientScriptInterface) {
            $this->clientScript = Yii::createObject($this->clientScript);
        }

        if ($this->requiresPjax()) {
            ob_start();
            ob_implicit_flush(false);

            $view = $this->getView();
            $view->clear();
            $view->beginPage();
            $view->head();
            $view->beginBody();

            if ($view->title !== null) {
                echo Html::tag('title', Html::encode($view->title));
            }
        } else {
            $options = $this->options;
            $tag = ArrayHelper::remove($options, 'tag', 'div');
            echo Html::beginTag(
                $tag,
                [
                    'data-pjax-container' => '',
                    'data-pjax-push-state' => $this->enablePushState,
                    'data-pjax-replace-state' => $this->enableReplaceState,
                    'data-pjax-timeout' => $this->timeout,
                    'data-pjax-scrollto' => $this->scrollTo,
                    ...$options,
                ],
            );
        }
    }

    /**
     * Registers the needed JavaScript.
     *
     * Delegates to [[clientScript]] when set. Prepares [[clientOptions]] from widget properties before delegating so
     * the client script receives a complete options array.
     */
    public function registerClientScript()
    {
        $id = $this->options['id'];
        $this->clientOptions['push'] = $this->enablePushState;
        $this->clientOptions['replace'] = $this->enableReplaceState;
        $this->clientOptions['timeout'] = $this->timeout;
        $this->clientOptions['scrollTo'] = $this->scrollTo;

        if (!isset($this->clientOptions['container']) && is_string($id) && $id !== '') {
            $this->clientOptions['container'] = "#$id";
        }

        if ($this->clientScript instanceof ClientScriptInterface) {
            $this->clientScript->register($this, $this->getView());
        }
    }

    public function run(): void
    {
        if (!$this->requiresPjax()) {
            echo Html::endTag(ArrayHelper::remove($this->options, 'tag', 'div'));
            $this->registerClientScript();

            return;
        }

        $view = $this->getView();

        $view->endBody();
        $view->endPage(true);

        $content = ob_get_clean();

        // only need the content enclosed within this widget
        $response = Yii::$app->getResponse();
        $response->clearOutputBuffers();
        $response->setStatusCode(200);

        $response->format = Response::FORMAT_HTML;
        $response->content = $content;

        $response->headers->setDefault('X-Pjax-Url', Yii::$app->request->url);

        Yii::$app->end();
    }

    /**
     * @return bool whether the current request requires pjax response from this widget
     */
    protected function requiresPjax()
    {
        $headers = Yii::$app->getRequest()->getHeaders();

        $id = $this->options['id'] ?? '';
        $result = false;

        if (is_string($id) && $id !== '') {
            $result = explode(' ', (string) $headers->get('X-Pjax-Container'))[0] === "#{$id}";
        }

        return $headers->get('X-Pjax') !== null && $result;
    }
}
