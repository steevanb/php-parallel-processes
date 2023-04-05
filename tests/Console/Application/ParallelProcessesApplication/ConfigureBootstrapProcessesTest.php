<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\ParallelProcessesApplication;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\BoostrapProcess,
    Tests\CreateLsProcessTrait
};

/** @covers \Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication::configureBootstrapProcesses */
final class ConfigureBootstrapProcessesTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testNoBootstrapProcesses(): void
    {
        $application = new TestParallelProcessesApplication();

        $process = $this->createLsProcess();
        $application->addProcess($process);
        $application->callConfigureBootstrapProcesses();

        static::assertFalse($process->getStartCondition()->hasConditions());
    }

    public function testBootstrapProcess(): void
    {
        $application = new TestParallelProcessesApplication();

        $process = $this->createLsProcess();
        $bootstrapProcess = new BoostrapProcess(['ls']);
        $application->addProcess($process);
        $application->addProcess($bootstrapProcess);
        $application->callConfigureBootstrapProcesses();

        static::assertSame($bootstrapProcess, $process->getStartCondition()->getProcessesSuccessful()[0]);
    }
}
