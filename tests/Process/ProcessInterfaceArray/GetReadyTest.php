<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\ProcessInterfaceArray;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\ProcessInterfaceArray,
    Tests\CreateLsProcessTrait
};

/** @covers \Steevanb\ParallelProcess\Process\ProcessInterfaceArray::getReady */
final class GetReadyTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testReady(): void
    {
        $process1 = $this->createLsProcess();
        $process2 = $this->createLsProcess();
        $processes = new ProcessInterfaceArray([$process1, $process2]);

        $process2->run();

        static::assertCount(1, $processes->getReady());
        static::assertSame($process1, $processes->getReady()[0]);
    }
}
