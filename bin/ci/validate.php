<?php

declare(strict_types=1);

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process,
    Process\TearDownProcess
};

require $_ENV['COMPOSER_GLOBAL_AUTOLOAD_FILE_NAME'];

$rootPath = dirname(__DIR__, 2);

$application = (new ParallelProcessesApplication())
    ->addProcess(new Process(['bin/ci/composer-normalize', '--dry-run'], $rootPath))
    ->addProcess(new Process(['bin/ci/composer-require-checker'], $rootPath))
    ->addProcess(new Process(['bin/ci/composer-validate'], $rootPath))
    ->addProcess(new Process(['bin/ci/lint-yaml'], $rootPath))
    ->addProcess(new Process(['bin/ci/phpcs'], $rootPath))
    ->addProcess(new Process(['bin/ci/phpdd'], $rootPath))
    ->addProcess(new Process(['bin/ci/phpstan'], $rootPath))
    ->addProcess(new Process(['bin/ci/shellcheck'], $rootPath))
    ->addProcess(new Process(['bin/ci/unused-scanner'], $rootPath))
    ->setRefreshInterval(100000)
    ->setTimeout(60)
    ->run();
