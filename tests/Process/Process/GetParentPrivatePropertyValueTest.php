<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\Process;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\Process,
    Tests\CreateLsProcessTrait
};

/** @covers \Steevanb\ParallelProcess\Process\Process::getParentPrivatePropertyValue */
final class GetParentPrivatePropertyValueTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testVisibility(): void
    {
        static::assertTrue($this->createReflection($this->createLsProcess())->isProtected());
    }

    public function testStatus(): void
    {
        $process = $this->createLsProcess();
        $reflection = $this->createReflection($process);
        $reflection->setAccessible(true);

        static::assertSame('ready', $reflection->getClosure($process)->call($process, 'status'));
    }

    public function testStarttime(): void
    {
        $process = $this->createLsProcess();
        $process->run();
        $reflection = $this->createReflection($process);
        $reflection->setAccessible(true);

        static::assertLessThanOrEqual(
            microtime(true),
            $reflection->getClosure($process)->call($process, 'starttime')
        );
    }

    private function createReflection(Process $process): \ReflectionMethod
    {
        return new \ReflectionMethod($process, 'getParentPrivatePropertyValue');
    }
}
