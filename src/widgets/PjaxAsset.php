<?php

declare(strict_types=1);

namespace yii\jquery\widgets;

use yii\jquery\web\YiiAsset;
use yii\web\AssetBundle;

/**
 * This asset bundle provides the JavaScript files required by [[Pjax]] widget.
 *
 * The bundled `jquery.pjax.js` asset is maintained locally so the package can support both jQuery `3.7` and `4.0`
 * without forcing an upstream `yii2-pjax` runtime dependency.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 0.1
 */
class PjaxAsset extends AssetBundle
{
    public $depends = [YiiAsset::class];
    public $js = ['jquery.pjax.js'];
    public $sourcePath = __DIR__ . '/../assets';
}
