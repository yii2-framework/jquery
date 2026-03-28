<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Import\FullyQualifiedStrictTypesFixer;

/** @var \Symplify\EasyCodingStandard\Configuration\ECSConfigBuilder $ecsConfigBuilder */
$ecsConfigBuilder = require __DIR__ . '/vendor/php-forge/coding-standard/config/ecs.php';

return $ecsConfigBuilder
    ->withSkip(
        [
            FullyQualifiedStrictTypesFixer::class,
        ],
    )
    ->withPaths(
        [
            __DIR__ . '/src',
            __DIR__ . '/tests',
        ],
    );
