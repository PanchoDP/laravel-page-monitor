<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    // Included Path
    ->withPaths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    // Excluded path
    ->withSkip([

    ])
    // Rector Sets
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        typeDeclarations: true,
        privatization: true,
        earlyReturn: true,
    )
    // PHP Configuration
    ->withPhpSets();
