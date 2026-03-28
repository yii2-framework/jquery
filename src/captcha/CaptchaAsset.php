<?php

declare(strict_types=1);

namespace yii\jquery\captcha;

use yii\jquery\web\YiiAsset;
use yii\web\AssetBundle;

/**
 * This asset bundle provides the javascript files needed for the [[Captcha]] widget.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 0.1
 */
class CaptchaAsset extends AssetBundle
{
    public $depends = [YiiAsset::class];
    public $js = ['yii.captcha.js'];
    public $sourcePath = __DIR__ . '/../assets';
}
