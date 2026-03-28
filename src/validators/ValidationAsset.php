<?php

declare(strict_types=1);

namespace yii\jquery\validators;

use yii\jquery\web\YiiAsset;
use yii\web\AssetBundle;

/**
 * This asset bundle provides the javascript files for client validation.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 0.1
 */
class ValidationAsset extends AssetBundle
{
    public $depends = [YiiAsset::class];
    public $js = ['yii.validation.js'];
    public $sourcePath = __DIR__ . '/../assets';
}
