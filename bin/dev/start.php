<?php

declare(strict_types=1);

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process
};
use Symfony\Component\Console\Input\ArgvInput;

require $_ENV['COMPOSER_GLOBAL_AUTOLOAD_FILE_NAME'];

$rootDir = dirname(__DIR__, 2);

$binDevDockerProcess = (new Process([$rootDir . '/bin/dev/docker']))
    ->setName('bin/dev/docker');

$binDevEnvProcess = (new Process([$rootDir . '/bin/dev/env']))
    ->setName('bin/dev/env');
$binDevEnvProcess->getStartCondition()->getProcessesSuccessful()->add($binDevDockerProcess);

(new ParallelProcessesApplication())
    ->addProcess($binDevDockerProcess)
    ->addProcess($binDevEnvProcess)
    ->setRefreshInterval(100000)
    ->run(new ArgvInput($argv));
