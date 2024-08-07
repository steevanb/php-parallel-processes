<?php

declare(strict_types=1);

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process,
    Process\ProcessInterfaceCollection
};
use Symfony\Component\Console\Input\ArgvInput;

$rootPath = dirname(__DIR__, 2);

require $rootPath . '/vendor/autoload.php';
require $rootPath . '/bin/DependenciesVersions.php';

function createPhpunitProcesses(DependenciesVersions $dependenciesVersions): ProcessInterfaceCollection
{
    $return = new ProcessInterfaceCollection();
    foreach ($dependenciesVersions->getPhpVersions()->toArray() as $phpVersion) {
        foreach ($dependenciesVersions->getSymfonyVersions()->toArray() as $symfonyVersion) {
            $return->add(createPhpunitProcess($phpVersion, $symfonyVersion));
        }
    }

    return $return;
}

function createPhpunitProcess(string $phpVersion, string $symfonyVersion): Process
{
    return (new Process([__DIR__ . '/phpunit', '--php=' . $phpVersion, '--symfony=' . $symfonyVersion]))
        ->setName('phpunit --php=' . $phpVersion . ' --symfony=' . $symfonyVersion);
}

$dependenciesVersions = new DependenciesVersions($argv);

(new ParallelProcessesApplication())
    ->addProcesses(createPhpunitProcesses($dependenciesVersions))
    ->run(new ArgvInput($dependenciesVersions->getFilteredArgv()->toArray()));
