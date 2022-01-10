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

function getAvailableSymfonyVersions(string $phpVersion): StringArray
{
    $return = new StringArray();
    foreach (new StringArray(['5.0', '5.1', '5.2', '5.3', '5.4', '6.0']) as $symfonyVersion) {
        if (isAvailable($phpVersion, $symfonyVersion)) {
            $return[] = $symfonyVersion;
        }
    }

    return $return;
}

function isAvailable(string $phpVersion, string $symfonyVersion): bool
{
    return ($phpVersion !== '7.4' || str_starts_with($symfonyVersion, '5.'));
}

function createPhpunitProcesses(string $phpVersion = null, string $symfonyVersion = null): ProcessArray
{
    $phpVersions = new StringArray(is_string($phpVersion) ? [$phpVersion] : ['7.4', '8.0', '8.1']);

    $return = new ProcessArray();
    foreach ($phpVersions as $loopPhpVersion) {
        $symfonyVersions = is_string($symfonyVersion)
            ? [$symfonyVersion]
            : getAvailableSymfonyVersions($loopPhpVersion)->toArray();

        foreach ($symfonyVersions as $loopSymfonyVersion) {
            if (isAvailable($loopPhpVersion, $loopSymfonyVersion)) {
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
