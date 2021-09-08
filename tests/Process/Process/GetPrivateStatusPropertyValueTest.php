<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\Process;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\Process,
    Tests\CreateLsProcessTrait
};

final class GetPrivateStatusPropertyValueTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testVisibility(): void
    {
        static::assertTrue($this->createReflection($this->createLsProcess())->isProtected());
    }

    public function testCall(): void
    {
        $process = $this->createLsProcess();
        $reflection = $this->createReflection($process);
        $reflection->setAccessible(true);

        static::assertSame('ready', $reflection->getClosure($process)->call($process));
    }

    private function createReflection(Process $process): \ReflectionMethod
    {
        return new \ReflectionMethod($process, 'getPrivateStatusPropertyValue');
    }
}
