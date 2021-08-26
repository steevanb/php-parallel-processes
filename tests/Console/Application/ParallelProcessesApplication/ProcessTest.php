<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\ParallelProcessesApplication;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process
};

class ProcessTest extends TestCase
{
    public function testAddProcess(): void
    {
        $application = new ParallelProcessesApplication();

        static::assertCount(0, $application->getProcesses());

        $process = new Process(['ls']);
        $application->addProcess($process);

        static::assertCount(1, $application->getProcesses());
        static::assertSame(spl_object_hash($process), spl_object_hash($application->getProcesses()[0]));
    }

    public function testAddMultipleProcess(): void
    {
        $application = new ParallelProcessesApplication();

        static::assertCount(0, $application->getProcesses());

        $process1 = new Process(['ls']);
        $application->addProcess($process1);

        $process2 = new Process(['pwd']);
        $application->addProcess($process2);

        $process3 = new Process(['echo']);
        $application->addProcess($process3);

        static::assertCount(3, $application->getProcesses());
        static::assertSame(spl_object_hash($process1), spl_object_hash($application->getProcesses()[0]));
        static::assertSame(spl_object_hash($process2), spl_object_hash($application->getProcesses()[1]));
        static::assertSame(spl_object_hash($process3), spl_object_hash($application->getProcesses()[2]));
    }
}
