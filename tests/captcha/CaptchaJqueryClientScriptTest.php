<?php

declare(strict_types=1);

namespace yii\jquery\tests\captcha;

use PHPUnit\Framework\Attributes\Group;
use Yii;
use yii\captcha\Captcha;
use yii\jquery\captcha\CaptchaJqueryClientScript;

/**
 * Unit tests for {@see CaptchaJqueryClientScript} jQuery client-side script.
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
#[Group('jquery')]
#[Group('captcha')]
final class CaptchaJqueryClientScriptTest extends \yii\jquery\tests\TestCase
{
    public function testGetClientOptions(): void
    {
        ob_start();

        $captcha = Yii::createObject(
            [
                'class' => Captcha::class,
                'name' => 'captcha',
                'captchaAction' => 'site/captcha',
                'imageOptions' => ['id' => 'captcha-image'],
            ],
        );

        ob_end_clean();

        $clientScript = new CaptchaJqueryClientScript();

        self::assertSame(
            [
                'refreshUrl' => '/index.php?r=site%2Fcaptcha&refresh=1',
                'hashKey' => 'yiiCaptcha/site/captcha',
            ],
            $clientScript->getClientOptions($captcha),
            'Should return correct client options.',
        );
    }

    public function testRegisterWithClientOptions(): void
    {
        ob_start();

        $captcha = Yii::createObject(
            [
                'class' => Captcha::class,
                'name' => 'captcha',
                'captchaAction' => 'site/captcha',
                'imageOptions' => ['id' => 'captcha-image'],
            ],
        );

        ob_end_clean();

        $view = Yii::$app->view;

        $clientScript = new CaptchaJqueryClientScript();

        $clientScript->register($captcha, $view);

        $js = implode('', $view->js[4] ?? []);

        self::assertSame(
            <<<'JS'
            jQuery('#captcha-image').yiiCaptcha({"refreshUrl":"\/index.php?r=site%2Fcaptcha\u0026refresh=1","hashKey":"yiiCaptcha\/site\/captcha"});
            JS,
            $js,
            'Should register jQuery captcha initialization script.',
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        Yii::$app->controller = new \yii\jquery\tests\data\controllers\SiteController('site', Yii::$app);
    }
}
