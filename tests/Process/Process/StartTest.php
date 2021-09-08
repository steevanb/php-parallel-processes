<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\Process;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Tests\CreateLsProcessTrait;

final class StartTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testDefaultParameters(): void
    {
        $process = $this->createLsProcess();
        $process->start();

        static::assertNull($process->getEnv());
        // Process::updateStatus() should not be called at this moment so execution time should be 0
        static::assertGreaterThanOrEqual(0, $process->getExecutionTime());
        static::assertLessThanOrEqual(50, $process->getExecutionTime());
    }

    public function testCallback(): void
    {
        $process = $this->createLsProcess();
        $hasBeenCalled = false;
        $process->start(
            function () use (&$hasBeenCalled): void {
                $hasBeenCalled = true;
            }
        );
        $process->wait();

        static::assertTrue($hasBeenCalled);
    }

    public function testEnv(): void
    {
        $process = $this->createLsProcess();
        $process->start(null, ['foo' => 'bar']);

        static::assertNull($process->getEnv());
    }
}
