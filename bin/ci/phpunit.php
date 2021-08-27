<?php

declare(strict_types=1);

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process
};
use Symfony\Component\Console\Input\ArgvInput;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

(new ParallelProcessesApplication())
    ->addProcess(new Process(['bin/ci/phpunit-php-7-4']))
    ->addProcess(new Process(['bin/ci/phpunit-php-8-0']))
    ->run(new ArgvInput($argv));
