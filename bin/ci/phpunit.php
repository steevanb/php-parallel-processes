<?php

declare(strict_types=1);

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process,
    Process\ProcessArray
};
use steevanb\PhpTypedArray\ScalarArray\StringArray;
use Symfony\Component\Console\Input\ArgvInput;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

function createPhpunitProcesses(string $phpVersion = null, string $symfonyVersion = null): ProcessArray
{
    $phpVersions = new StringArray(is_string($phpVersion) ? [$phpVersion] : ['7.4', '8.0']);
    $symfonyVersions = new StringArray(is_string($symfonyVersion) ? [$symfonyVersion] : ['5.0', '5.1', '5.2', '5.3']);

    $return = new ProcessArray();
    foreach ($phpVersions as $loopPhpVersion) {
        foreach ($symfonyVersions as $loopSymfonyVersion) {
            if (
                in_array($loopPhpVersion, ['7.4', '8.0'])
                && in_array($loopSymfonyVersion, ['5.0', '5.1', '5.2', '5.3'])
            ) {
                $return[] = createPhpunitProcess($loopPhpVersion, $loopSymfonyVersion);
            }
        }
    }

    return $return;
}

function createPhpunitProcess(string $phpVersion, string $symfonyVersion): Process
{
    return (new Process([__DIR__ . '/phpunit', '--php=' . $phpVersion, '--symfony=' . $symfonyVersion]))
        ->setName('phpunit --php=' . $phpVersion . ' --symfony=' . $symfonyVersion);
}

$phpVersion = null;
$symfonyVersion = null;
$applicationArgv = new StringArray();
foreach ($argv as $arg) {
    if (substr($arg, 0, 6) === '--php=') {
        $phpVersion = substr($arg, 6);
    } elseif (substr($arg, 0, 10) === '--symfony=') {
        $symfonyVersion = substr($arg, 10);
    } else {
        $applicationArgv[] = $arg;
    }
}

(new ParallelProcessesApplication())
    ->addProcesses(createPhpunitProcesses($phpVersion, $symfonyVersion))
    ->run(new ArgvInput($applicationArgv->toArray()));
