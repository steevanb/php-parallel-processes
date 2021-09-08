<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\StartCondition;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\Process,
    Process\StartCondition
};

final class CanBeStartedTest extends TestCase
{
    public function testCanBeStarted(): void
    {
        $startCondition = new StartCondition();

        static::assertTrue($startCondition->canBeStarted());
    }

    public function testHaveOneNotTerminated(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['ls']);
        $startCondition->addProcessTerminated($process);

        static::assertFalse($process->isTerminated());
        static::assertFalse($startCondition->canBeStarted());
    }

    public function testHaveOneTerminated(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['ls']);
        $process->run();
        $startCondition->addProcessTerminated($process);

        static::assertTrue($process->isTerminated());
        static::assertTrue($startCondition->canBeStarted());
    }

    public function testHaveOneNotSuccessful(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['ls']);
        $startCondition->addProcessSuccessful($process);

        static::assertFalse($process->isTerminated());
        static::assertFalse($startCondition->canBeStarted());
    }

    public function testHaveOneSuccessful(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['ls']);
        $process->mustRun();
        $startCondition->addProcessSuccessful($process);

        static::assertTrue($process->isTerminated());
        static::assertTrue($process->isSuccessful());
        static::assertTrue($startCondition->canBeStarted());
    }

    public function testHaveOneNotFailed(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['unknown-command']);
        $startCondition->addProcessFailed($process);

        static::assertFalse($process->isTerminated());
        static::assertFalse($startCondition->canBeStarted());
    }

    public function testHaveOneFailed(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['unknown-command']);
        $process->run();
        $startCondition->addProcessFailed($process);

        static::assertTrue($process->isTerminated());
        static::assertFalse($process->isSuccessful());
        static::assertTrue($startCondition->canBeStarted());
    }
}
