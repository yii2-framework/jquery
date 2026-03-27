<?php

declare(strict_types=1);

namespace yii\jquery\tests\data\controllers;

use yii\captcha\CaptchaAction;
use yii\web\Controller;

final class SiteController extends Controller
{
    public function actions(): array
    {
        return [
            'captcha' => ['class' => CaptchaAction::class],
        ];
    }
}
