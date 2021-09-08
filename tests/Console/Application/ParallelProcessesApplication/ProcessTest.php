<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\ParallelProcessesApplication;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process,
    Tests\CreateLsProcessTrait
};

final class ProcessTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testAddProcess(): void
    {
        $application = new ParallelProcessesApplication();

        static::assertCount(0, $application->getProcesses());

        $process = $this->createLsProcess();
        $application->addProcess($process);

        static::assertCount(1, $application->getProcesses());
        static::assertSame(spl_object_hash($process), spl_object_hash($application->getProcesses()[0]));
    }

    public function testAddMultipleProcess(): void
    {
        $application = new ParallelProcessesApplication();

        static::assertCount(0, $application->getProcesses());

        $process1 = $this->createLsProcess();
        $application->addProcess($process1);

        $process2 = $this->createLsProcess();
        $application->addProcess($process2);

        $process3 = $this->createLsProcess();
        $application->addProcess($process3);

        static::assertCount(3, $application->getProcesses());
        static::assertSame($process1, $application->getProcesses()[0]);
        static::assertSame($process2, $application->getProcesses()[1]);
        static::assertSame($process3, $application->getProcesses()[2]);
    }
}
