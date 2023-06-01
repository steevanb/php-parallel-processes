<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\ProcessInterfaceCollection;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\ProcessInterfaceCollection,
    Tests\CreateLsProcessTrait,
    Tests\CreateSleepProcessTrait
};

/** @covers \Steevanb\ParallelProcess\Process\ProcessInterfaceCollection::countRunning */
final class CountRunningTest extends TestCase
{
    use CreateLsProcessTrait;
    use CreateSleepProcessTrait;

    public function test0(): void
    {
        $process1 = $this->createLsProcess();
        $process2 = $this->createLsProcess();
        $processes = new ProcessInterfaceCollection([$process1, $process2]);

        static::assertSame(0, $processes->countRunning());
    }

    public function test1(): void
    {
        $process1 = $this->createSleepProcess();
        $process2 = $this->createLsProcess();
        $processes = new ProcessInterfaceCollection([$process1, $process2]);

        $process1->start();

        static::assertSame(1, $processes->countRunning());

        $process1->stop();
    }

    public function test2(): void
    {
        $process1 = $this->createSleepProcess();
        $process2 = $this->createSleepProcess();
        $processes = new ProcessInterfaceCollection([$process1, $process2]);

        $process1->start();
        $process2->start();

        static::assertSame(2, $processes->countRunning());

        $process1->stop();
        $process2->stop();
    }
}
