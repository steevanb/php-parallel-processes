<?php

declare(strict_types=1);

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\BootstrapProcess,
    Process\Process,
    Process\TearDownProcess
};
use Symfony\Component\Console\Input\ArgvInput;

require $_ENV['COMPOSER_GLOBAL_AUTOLOAD_FILE_NAME'];

$bootstrapProcess = (new BootstrapProcess(['sleep', '1']))
    ->setName('Check installation');

$process1 = (new Process(['sleep', '2']))
    ->setName('Start docker stack');

$process2 = (new Process(['sleep', '1']))
    ->setName('Install dependencies');
$process2->getStartCondition()->getProcessesSuccessful()->add($process1);

$process3 = (new Process(['sleep', '2']))
    ->setName('Create database');
$process3->getStartCondition()->getProcessesSuccessful()->add($process1);

$process4 = (new Process(['sleep', '1']))
    ->setName('Clear cache');

$tearDownProcess = (new TearDownProcess(['sleep', '1']))
    ->setName('Wait everything is started');

(new ParallelProcessesApplication())
    ->addProcess($bootstrapProcess)
    ->addProcess($process1)
    ->addProcess($process2)
    ->addProcess($process3)
    ->addProcess($process4)
    ->addProcess($tearDownProcess)
    ->run(new ArgvInput($argv));
