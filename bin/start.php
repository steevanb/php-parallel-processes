<?php

declare(strict_types=1);

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process
};
use Symfony\Component\Console\Input\ArgvInput;

require $_ENV['COMPOSER_GLOBAL_AUTOLOAD_FILE_NAME'];

$rootDir = dirname(__DIR__);

$ciDockerProcess = (new Process([$rootDir . '/bin/ci/docker']))
    ->setName('bin/ci/docker');

$ciEnvProcess = (new Process([$rootDir . '/bin/ci/env']))
    ->setName('bin/ci/env');
$ciEnvProcess->getStartCondition()->getProcessesSuccessful()->add($ciDockerProcess);

(new ParallelProcessesApplication())
    ->addProcess($ciDockerProcess)
    ->addProcess($ciEnvProcess)
    ->setRefreshInterval(50000)
    ->run(new ArgvInput($argv));
