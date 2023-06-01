<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\ParallelProcessesApplication;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\TearDownProcess,
    Tests\CreateLsProcessTrait
};

/** @covers \Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication::configureTearDownProcesses */
final class ConfigureTearDownProcessesTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testNoTearDownProcesses(): void
    {
        $application = new TestParallelProcessesApplication();

        $process = $this->createLsProcess();
        $application->addProcess($process);
        $application->callConfigureTearDownProcesses();

        static::assertFalse($process->getStartCondition()->hasConditions());
    }

    public function testBootstrapProcess(): void
    {
        $application = new TestParallelProcessesApplication();

        $process = $this->createLsProcess();
        $tearDownProcess = new TearDownProcess(['ls']);
        $application->addProcess($process);
        $application->addProcess($tearDownProcess);
        $application->callConfigureTearDownProcesses();

        static::assertSame($process, $tearDownProcess->getStartCondition()->getProcessesTerminated()->get(0));
    }
}
