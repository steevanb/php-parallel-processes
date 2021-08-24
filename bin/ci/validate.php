<?php

declare(strict_types=1);

use Steevanb\ParallelProcess\{
    Console\ParallelProcessesApplication,
    Process\ParallelProcess
};
use Symfony\Component\Process\Process;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

(new ParallelProcessesApplication())
    ->addParallelProcess(new ParallelProcess(new Process(['bin/ci/composer-require-checker'])))
    ->addParallelProcess(new ParallelProcess(new Process(['bin/ci/composer-validate'])))
    ->addParallelProcess(new ParallelProcess(new Process(['bin/ci/phpcs'])))
    ->addParallelProcess(new ParallelProcess(new Process(['bin/ci/phpdd'])))
    ->addParallelProcess(new ParallelProcess(new Process(['bin/ci/phpstan'])))
    ->run();
