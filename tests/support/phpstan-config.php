<?php

declare(strict_types=1);

return [
    'phpstan' => [
        'application_type' => \yii\web\Application::class,
    ],
    'components' => [
        'assetManager' => ['class' => \yii\web\AssetManager::class],
        'request' => ['class' => \yii\web\Request::class],
        'urlManager' => ['class' => \yii\web\UrlManager::class],
        'view' => ['class' => \yii\web\View::class],
    ],
];
