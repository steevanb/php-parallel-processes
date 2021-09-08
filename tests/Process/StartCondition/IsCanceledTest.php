<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\StartCondition;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\Process,
    Process\StartCondition
};

final class IsCanceledTest extends TestCase
{
    public function testIsCanceled(): void
    {
        $startCondition = new StartCondition();

        static::assertFalse($startCondition->isCanceled());
    }

    public function testHaveOneNotStartedNotSuccessful(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['ls']);
        $startCondition->addProcessSuccessful($process);

        static::assertFalse($process->isSuccessful());
        static::assertFalse($startCondition->isCanceled());
    }

    public function testHaveOneSuccessful(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['ls']);
        $process->mustRun();
        $startCondition->addProcessSuccessful($process);

        static::assertTrue($process->isSuccessful());
        static::assertFalse($startCondition->isCanceled());
    }

    public function testHaveOneNotSuccessful(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['unknown-command']);
        $process->run();
        $startCondition->addProcessSuccessful($process);

        static::assertFalse($process->isSuccessful());
        static::assertTrue($startCondition->isCanceled());
    }

    public function testHaveOneNotStartedNotFailed(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['unknown-command']);
        $startCondition->addProcessFailed($process);

        static::assertFalse($process->isSuccessful());
        static::assertFalse($startCondition->isCanceled());
    }

    public function testHaveOneFailed(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['unknown-command']);
        $process->run();
        $startCondition->addProcessFailed($process);

        static::assertFalse($process->isSuccessful());
        static::assertFalse($startCondition->isCanceled());
    }

    public function testHaveOneNotFailed(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['ls']);
        $process->mustRun();
        $startCondition->addProcessFailed($process);

        static::assertTrue($process->isSuccessful());
        static::assertTrue($startCondition->isCanceled());
    }
}
