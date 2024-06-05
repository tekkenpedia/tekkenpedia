<?php

declare(strict_types=1);

$projectPath = __DIR__ . '/../..';

return [
    'composerJsonPath' => $projectPath . '/composer.json',
    'vendorPath' => $projectPath . '/vendor',
    'scanDirectories' => [
        $projectPath . '/bin',
        $projectPath . '/config',
        $projectPath . '/public',
        $projectPath . '/src',
        $projectPath . '/tests',
    ],
    'skipPackages' => [
        // Ce n'est pas utilisé directement src/, mais c'est requis
        'symfony/dotenv',
        // Ce n'est pas utilisé directement src/, mais c'est requis
        'symfony/flex',
        // C'est utilisé dans public/index.php mais pas dans src/
        'symfony/runtime',
        // C'est requis par symfony/translation pour lire les fichiers dans translations/
        'symfony/yaml'
    ],
    'requireDev' => false
];
