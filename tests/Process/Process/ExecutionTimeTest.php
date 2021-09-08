<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\Process;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Exception\ParallelProcessException,
    Process\Process,
    Tests\CreateLsProcessTrait
};

final class ExecutionTimeTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testNotStarted(): void
    {
        $process = $this->createLsProcess();

        $this->expectException(ParallelProcessException::class);
        $this->expectExceptionCode(0);
        $this->expectErrorMessage('Process must be started before calling "getExecutionTime()".');
        $process->getExecutionTime();
    }

    public function testStarted(): void
    {
        $process = new Process(['sleep', '0.2']);
        $process->start();

        // Process::updateStatus() should not be called at this moment so execution time should be 0
        static::assertLessThan(50, $process->getExecutionTime());
    }

    public function testTerminated(): void
    {
        $process = new Process(['sleep', '0.2']);
        $process->mustRun();

        static::assertGreaterThanOrEqual(200, $process->getExecutionTime());
    }
}
