<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Process;

use Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication;

class ProcessFactory
{
    public static function createRemoveDirectoryProcess(
        string $directory,
        ParallelProcessesApplication $application,
        string $name = null,
        string $cwd = null
    ): ?Process {
        if (is_dir($directory)) {
            $return = new Process(['rm', '-rf', $directory], $cwd);
            if (is_string($name)) {
                $return->setName($name);
            }

            $application->addProcess($return);
        } else {
            $return = null;
        }

        return $return;
    }
}
