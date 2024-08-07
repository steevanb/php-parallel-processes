<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\ParallelProcessesApplication;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process,
    Process\TearDownProcess
};
use Symfony\Component\Console\{
    Input\ArrayInput,
    Output\NullOutput
};

/** @coversNothing */
final class AllUseCasesTest extends TestCase
{
    public function testAllUseCases(): void
    {
        $rootDir = dirname(__DIR__, 4);

        $process1 = new Process(['pwd'], $rootDir);

        $process2 = new Process(['unknown-command'], $rootDir);
        $process2->getStartCondition()->addProcessSuccessful($process1);

        $process3 = new Process(['pwd'], $rootDir);
        $process3->getStartCondition()->addProcessSuccessful($process2);

        $process4 = new Process(['pwd'], $rootDir);
        $process4->getStartCondition()->addProcessSuccessful($process3);

        $tearDownProcess = new TearDownProcess(['pwd']);

        $exitCode = (new ParallelProcessesApplication())
            ->setRefreshInterval(100)
            ->setAutoExit(false)
            ->addProcess($process1)
            ->addProcess($process2)
            ->addProcess($process3)
            ->addProcess($process4)
            ->addProcess($tearDownProcess)
            ->run(new ArrayInput([]), new NullOutput());

        static::assertSame(1, $exitCode);

        static::assertTrue($process1->isTerminated());
        static::assertTrue($process1->isSuccessful());

        static::assertTrue($process2->isTerminated());
        static::assertFalse($process2->isSuccessful());

        static::assertFalse($process3->isStarted());

        static::assertFalse($process4->isStarted());

        static::assertTrue($tearDownProcess->isTerminated());
        static::assertTrue($tearDownProcess->isSuccessful());
    }
}
