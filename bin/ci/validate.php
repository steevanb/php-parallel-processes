<?php

declare(strict_types=1);

use Steevanb\ParallelProcess\{
    Console\ParallelProcessesApplication,
    Process\ParallelProcess
};
use Symfony\Component\Process\Process;

$rootDir = dirname(__DIR__, 2);

require $rootDir . '/vendor/autoload.php';

(new ParallelProcessesApplication())
    ->addParallelProcess(new ParallelProcess(new Process(['bin/ci/composer-require-checker'], $rootDir)))
    ->addParallelProcess(new ParallelProcess(new Process(['bin/ci/composer-validate'], $rootDir)))
    ->addParallelProcess(new ParallelProcess(new Process(['bin/ci/phpcs'], $rootDir)))
    ->addParallelProcess(new ParallelProcess(new Process(['bin/ci/phpdd'], $rootDir)))
    ->addParallelProcess(new ParallelProcess(new Process(['bin/ci/phpstan'], $rootDir)))
    ->run();
