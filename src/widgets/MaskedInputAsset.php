<?php

declare(strict_types=1);

namespace yii\jquery\widgets;

use yii\jquery\web\YiiAsset;
use yii\web\AssetBundle;

/**
 * The asset bundle for the [[MaskedInput]] widget.
 *
 * Includes client assets of [jQuery input mask plugin](https://github.com/RobinHerbots/Inputmask).
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 0.1
 */
class MaskedInputAsset extends AssetBundle
{
    public $depends = [YiiAsset::class];
    public $js = ['jquery.inputmask.js'];
    public $sourcePath = '@npm/inputmask/dist';
}
