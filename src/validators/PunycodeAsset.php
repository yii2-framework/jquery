<?php

declare(strict_types=1);

namespace yii\jquery\validators;

use yii\web\AssetBundle;

/**
 * Provides the [Punycode.js](https://github.com/mathiasbynens/punycode.js) library for IDN validation in
 * [[EmailValidatorJqueryClientScript]] and [[UrlValidatorJqueryClientScript]].
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 0.1
 */
class PunycodeAsset extends AssetBundle
{
    public $js = ['punycode.js'];
    public $sourcePath = '@npm/punycode';
}
