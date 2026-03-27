<?php

declare(strict_types=1);

namespace yii\jquery\grid;

use yii\jquery\web\YiiAsset;
use yii\web\AssetBundle;

/**
 * This asset bundle provides the javascript files for the [[GridView]] widget.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 0.1
 */
class GridViewAsset extends AssetBundle
{
    public $depends = [YiiAsset::class];
    public $js = ['yii.gridView.js'];
    public $sourcePath = __DIR__ . '/../assets';
}
