<?php

declare(strict_types=1);

namespace yii\jquery\tests\captcha;

use PHPUnit\Framework\Attributes\Group;
use Yii;
use yii\base\DynamicModel;
use yii\captcha\CaptchaValidator;
use yii\helpers\Json;
use yii\jquery\captcha\CaptchaValidatorJqueryClientScript;
use yii\jquery\tests\TestCase;
use yii\jquery\validators\ValidationAsset;

/**
 * Unit tests for {@see CaptchaValidatorJqueryClientScript} jQuery client-side validation script.
 *
 * @author Wilmer Arambula <terabytesoftw@gmail.com>
 * @since 0.1
 */
#[Group('jquery')]
#[Group('captcha')]
#[Group('validators')]
final class CaptchaValidatorJqueryClientScriptTest extends TestCase
{
    public function testGetClientOptions(): void
    {
        $validator = Yii::createObject(
            [
                'class' => CaptchaValidator::class,
                'captchaAction' => 'site/captcha',
            ],
        );

        $model = new DynamicModel(['captcha']);
        $clientScript = new CaptchaValidatorJqueryClientScript();

        $options = $clientScript->getClientOptions($validator, $model, 'captcha');

        self::assertSame(
            'yiiCaptcha/site/captcha',
            $options['hashKey'],
            "Should match expected 'hashKey'.",
        );
        self::assertFalse(
            $options['caseSensitive'],
            "Should return 'caseSensitive' as 'false' by default.",
        );
        self::assertSame(
            'The verification code is incorrect.',
            $options['message'],
            "Should return default error 'message'.",
        );
    }

    public function testRegister(): void
    {
        $validator = Yii::createObject(
            [
                'class' => CaptchaValidator::class,
                'captchaAction' => 'site/captcha',
            ],
        );

        $model = new DynamicModel(['captcha']);
        $view = Yii::$app->view;
        $clientScript = new CaptchaValidatorJqueryClientScript();
        $options = Json::htmlEncode($clientScript->getClientOptions($validator, $model, 'captcha'));

        $js = $clientScript->register($validator, $model, 'captcha', $view);

        self::assertArrayHasKey(
            ValidationAsset::class,
            $view->assetBundles,
            'Should register ValidationAsset.',
        );
        self::assertSame(
            <<<JS
            yii.validation.captcha(value, messages, $options);
            JS,
            $js,
            'Should return correct captcha validation script.',
        );
    }
}
