<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\ParallelProcessesApplication;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Tests\CreateSleepProcessTrait
};

/** @covers \Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication::handleSignal */
final class HandleSignalTest extends TestCase
{
    use CreateSleepProcessTrait;

    public function testSigint(): void
    {
        $application = new ParallelProcessesApplication();

        $process1 = $this->createSleepProcess();
        $process2 = $this->createSleepProcess();

        $application
            ->addProcess($process1)
            ->addProcess($process2);

        $application->handleSignal(SIGINT);

        static::assertTrue($process1->isCanceled());
        static::assertTrue($process2->isCanceled());
    }
}
