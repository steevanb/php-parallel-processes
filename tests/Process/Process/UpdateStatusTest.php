<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\Process;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Exception\ParallelProcessException,
    Process\Process,
    Tests\CreateLsProcessTrait
};

final class UpdateStatusTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testVisibility(): void
    {
        $reflection = $this->createReflection($this->createLsProcess());

        static::assertTrue($reflection->isProtected());
    }

    public function testProcessNotStarted(): void
    {
        $process = $this->createLsProcess();
        $reflection = $this->createReflection($process);
        $reflection->setAccessible(true);

        static::assertNull($reflection->getClosure($process)->call($process, false));
        $this->expectException(ParallelProcessException::class);
        $this->expectExceptionCode(0);
        $this->expectExceptionMessage('Process must be started before calling "getExecutionTime()".');
        $process->getExecutionTime();
    }

    public function testProcessTerminated(): void
    {
        $process = $this->createLsProcess();
        $reflection = $this->createReflection($process);
        $reflection->setAccessible(true);
        $process->mustRun();

        static::assertNull($reflection->getClosure($process)->call($process, false));
        static::assertGreaterThanOrEqual(1, $process->getExecutionTime());
    }

    private function createReflection(Process $process): \ReflectionMethod
    {
        return new \ReflectionMethod($process, 'updateStatus');
    }
}
