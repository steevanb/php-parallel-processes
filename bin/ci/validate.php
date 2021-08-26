<?php

declare(strict_types=1);

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process
};
use Symfony\Component\Console\Input\ArgvInput;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

(new ParallelProcessesApplication())
    ->addProcess(new Process(['bin/ci/composer-require-checker']))
    ->addProcess(new Process(['bin/ci/composer-validate']))
    ->addProcess(new Process(['bin/ci/phpcs']))
    ->addProcess(new Process(['bin/ci/phpdd']))
    ->addProcess(new Process(['bin/ci/phpstan']))
    ->run(new ArgvInput($argv));
