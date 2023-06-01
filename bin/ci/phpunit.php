<?php

declare(strict_types=1);

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process,
    Process\ProcessInterfaceCollection
};
use Steevanb\PhpCollection\ScalarCollection\StringCollection;
use Symfony\Component\Console\Input\ArgvInput;

require dirname(__DIR__, 2) . '/vendor/autoload.php';

function getAvailableSymfonyVersions(): StringCollection
{
    $return = new StringCollection();
    foreach (new StringCollection(['6.1', '6.2', '6.3']) as $symfonyVersion) {
        $return->add($symfonyVersion);
    }

    return $return;
}

function createPhpunitProcesses(string $phpVersion = null, string $symfonyVersion = null): ProcessInterfaceCollection
{
    $phpVersions = new StringCollection(is_string($phpVersion) ? [$phpVersion] : ['8.1', '8.2']);

    $return = new ProcessInterfaceCollection();
    foreach ($phpVersions as $loopPhpVersion) {
        $symfonyVersions = is_string($symfonyVersion)
            ? [$symfonyVersion]
            : getAvailableSymfonyVersions()->toArray();

        foreach ($symfonyVersions as $loopSymfonyVersion) {
            $return->add(createPhpunitProcess($loopPhpVersion, $loopSymfonyVersion));
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
$applicationArgv = new StringCollection();
foreach ($argv as $arg) {
    if (str_starts_with($arg, '--php=')) {
        $phpVersion = substr($arg, 6);
    } elseif (str_starts_with($arg, '--symfony=')) {
        $symfonyVersion = substr($arg, 10);
    } else {
        $applicationArgv->add($arg);
    }
}

(new ParallelProcessesApplication())
    ->addProcesses(createPhpunitProcesses($phpVersion, $symfonyVersion))
    ->run(new ArgvInput($applicationArgv->toArray()));
