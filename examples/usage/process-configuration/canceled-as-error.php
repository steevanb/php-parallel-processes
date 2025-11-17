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

$errorProcess = (new Process(['unknown-command']))
    ->setName('This process will fail')
    ->setFailureErrorOutputVerbosity(OutputInterface::VERBOSITY_VERBOSE)
    ->setSpreadErrorToApplicationExitCode(false);

$canceledProcess = (new Process(['cancel-me']))
    ->setName('This proces will be canceled')
    ->setCanceledAsError(
        ($_ENV['PP_CANCELED_AS_ERROR'] ?? 'true') === 'true'
    );

$canceledProcess
    ->getStartCondition()
    ->getProcessesSuccessful()
    ->add($errorProcess);

(new ParallelProcessesApplication())
    ->addProcess($errorProcess)
    ->addProcess($canceledProcess)
    ->run(new ArgvInput($argv));
