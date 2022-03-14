<?php

/**
 * This file is not used by PHPUnit tests
 * You can use it this command for example:
 * docker run --rm -it -v $(pwd):/composer/vendor/steevanb/php-parallel-processes -v $(pwd):/app
 * steevanb/php-parallel-processes:0.7.0-alpine php /app/tests/Processes.php
 */

declare(strict_types=1);

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process
};
use Symfony\Component\Console\Input\ArgvInput;

require $_ENV['COMPOSER_GLOBAL_AUTOLOAD_FILE_NAME'];

$rootDir = dirname(__DIR__, 2);

$process1 = new Process(['sleep', '1'], $rootDir);

$process2 = new Process(['pwd'], $rootDir);
$process2->getStartCondition()->addProcessSuccessful($process1);

$process3 = new Process(['pwd'], $rootDir);
$process3->getStartCondition()->addProcessSuccessful($process2);

(new ParallelProcessesApplication())
    ->addProcess($process1)
    ->addProcess($process2)
    ->addProcess($process3)
    ->setRefreshInterval(1)
    ->setTheme(new \Steevanb\ParallelProcess\Console\Application\Theme\SummaryTheme())
    ->run(new ArgvInput($argv));
