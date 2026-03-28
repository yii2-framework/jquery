<?php

declare(strict_types=1);

namespace yii\jquery\widgets;

use yii\jquery\web\YiiAsset;
use yii\web\AssetBundle;

/**
 * The asset bundle for the [[ActiveForm]] widget.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 0.1
 */
class ActiveFormAsset extends AssetBundle
{
    public $depends = [YiiAsset::class];
    public $js = ['yii.activeForm.js'];
    public $sourcePath = __DIR__ . '/../assets';
}
