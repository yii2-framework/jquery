<?php

declare(strict_types=1);

namespace yii\jquery\web;

use yii\web\AssetBundle;

/**
 * Provides the Yii JavaScript library (`yii.js`) with a jQuery dependency.
 *
 * Loads `yii.js` from the package assets directory and depends on {@see JqueryAsset} so that jQuery is available
 * before any Yii client-side code executes.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 0.1
 */
class YiiAsset extends AssetBundle
{
    public $depends = [JqueryAsset::class];
    public $js = ['yii.js'];
    public $sourcePath = __DIR__ . '/../assets';
}
