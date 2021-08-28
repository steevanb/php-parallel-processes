<?php

declare(strict_types=1);

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process
};
use Symfony\Component\Console\Input\ArgvInput;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

(new ParallelProcessesApplication())
    ->addProcess(new Process([__DIR__ . '/composer-require-checker']))
    ->addProcess(new Process([__DIR__ . '/composer-validate']))
    ->addProcess(new Process([__DIR__ . '/phpcs']))
    ->addProcess(new Process([__DIR__ . '/phpdd']))
    ->addProcess(new Process([__DIR__ . '/phpstan']))
    ->addProcess(new Process([__DIR__ . '/phpunit-php-7-4-symfony-5-0']))
    ->addProcess(new Process([__DIR__ . '/phpunit-php-7-4-symfony-5-1']))
    ->addProcess(new Process([__DIR__ . '/phpunit-php-7-4-symfony-5-2']))
    ->addProcess(new Process([__DIR__ . '/phpunit-php-7-4-symfony-5-3']))
    ->addProcess(new Process([__DIR__ . '/phpunit-php-8-0-symfony-5-0']))
    ->addProcess(new Process([__DIR__ . '/phpunit-php-8-0-symfony-5-1']))
    ->addProcess(new Process([__DIR__ . '/phpunit-php-8-0-symfony-5-2']))
    ->addProcess(new Process([__DIR__ . '/phpunit-php-8-0-symfony-5-3']))
    ->run(new ArgvInput($argv));
