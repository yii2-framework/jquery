<?php

declare(strict_types=1);

namespace yii\jquery\widgets;

use yii\jquery\web\YiiAsset;
use yii\web\AssetBundle;

/**
 * This asset bundle provides the javascript files required by [[Pjax]] widget.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 0.1
 */
class PjaxAsset extends AssetBundle
{
    public $depends = [YiiAsset::class];
    public $js = ['jquery.pjax.js'];
    public $sourcePath = '@npm/yii2-pjax';
}
