<?php

declare(strict_types=1);

namespace yii\jquery\web;

use yii\web\AssetBundle;

/**
 * This asset bundle provides the [jQuery](https://jquery.com/) JavaScript library.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 0.1
 */
class JqueryAsset extends AssetBundle
{
    public $js = ['jquery.js'];
    public $sourcePath = '@npm/jquery/dist';
}
