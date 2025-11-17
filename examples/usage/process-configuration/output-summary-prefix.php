<?php

declare(strict_types=1);

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process,
};
use Symfony\Component\Console\{
    Input\ArgvInput,
    Output\OutputInterface
};

require $_ENV['COMPOSER_GLOBAL_AUTOLOAD_FILE_NAME'];

(new ParallelProcessesApplication())
    ->addProcess(
        (new Process(['pwd']))
            ->setOutputSummaryPrefix('--Output summary prefix--')
            ->setStandardOutputVerbosity(OutputInterface::VERBOSITY_NORMAL)
    )
    ->addProcess(
        (new Process(['pwd']))
            ->setOutputSummaryPrefix('--Another output summary prefix--')
            ->setStandardOutputVerbosity(OutputInterface::VERBOSITY_NORMAL)
    )
    ->run(new ArgvInput($argv));
