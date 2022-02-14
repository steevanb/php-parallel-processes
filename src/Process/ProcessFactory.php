<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Process;

use Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication;
use steevanb\PhpTypedArray\ScalarArray\StringArray;

class ProcessFactory
{
    public static function createRemoveDirectoryProcess(
        string $path,
        ParallelProcessesApplication $application,
        string $name = null,
        string $cwd = null
    ): ?Process {
        if (is_dir($path)) {
            $return = static::createProcess(new StringArray(['rm', '-rf', $path]), $application, $name, $cwd);
        } else {
            $return = null;
        }

        return $return;
    }

    public static function createRemoveFileProcess(
        string $filePathname,
        ParallelProcessesApplication $application,
        string $name = null,
        string $cwd = null
    ): ?Process {
        if (is_file($filePathname)) {
            $return = static::createProcess(new StringArray(['rm', $filePathname]), $application, $name, $cwd);
        } else {
            $return = null;
        }

        return $return;
    }

    protected static function createProcess(
        StringArray $command,
        ParallelProcessesApplication $application,
        string $name = null,
        string $cwd = null
    ): Process {
        $return = new Process($command->toArray(), $cwd);
        if (is_string($name)) {
            $return->setName($name);
        }

        $application->addProcess($return);

        return $return;
    }
}
