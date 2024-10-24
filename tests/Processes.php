<?php

/**
 * This file is not used by PHPUnit tests
 * You can use it this command for example:
 * docker run --rm -it -v $(pwd):/composer/vendor/steevanb/php-parallel-processes -v $(pwd):/app
 * steevanb/php-parallel-processes:0.11.0-alpine php /app/tests/Processes.php
 */

declare(strict_types=1);

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process
};

require $_ENV['COMPOSER_GLOBAL_AUTOLOAD_FILE_NAME'];
require __DIR__ . '/../vendor/autoload.php';

$rootDir = dirname(__DIR__, 2);

$process1 = new Process(['sleep', '63'], $rootDir);

$process2 = new Process(['pwd'], $rootDir);
$process2->getStartCondition()->getProcessesSuccessful()->add($process1);

$process3 = new Process(['pwd'], $rootDir);
$process3->getStartCondition()->getProcessesSuccessful()->add($process2);

(new ParallelProcessesApplication())
    ->addProcess($process1)
    ->addProcess($process2)
    ->addProcess($process3)
    ->setRefreshInterval(1000000)
    ->run();
