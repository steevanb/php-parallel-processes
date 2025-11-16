<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\Process;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Process\Process;

/** @covers \Steevanb\ParallelProcess\Process\Process::__construct */
final class ConstructTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $process = new Process(['ls'], 'bar');

        static::assertSame("'ls'", $process->getCommandLine());
        static::assertSame('bar', $process->getWorkingDirectory());
        static::assertCount(0, $process->getEnv());
        static::assertNull($process->getInput());
        static::assertSame(60.0, $process->getTimeout());
        static::assertSame('ls', $process->getName());
        static::assertSame(128, $process->getStandardOutputVerbosity());
        static::assertSame(128, $process->getErrorOutputVerbosity());
        static::assertSame(128, $process->getCanceledOutputVerbosity());
        static::assertSame(32, $process->getFailureStandardOutputVerbosity());
        static::assertSame(32, $process->getFailureErrorOutputVerbosity());
        static::assertFalse($process->isCanceled());
        static::assertTrue($process->isCanceledAsError());
        static::assertTrue($process->isSpreadErrorToApplicationExitCode());

        static::assertCount(0, $process->getStartCondition()->getProcessesSuccessful());
        static::assertCount(0, $process->getStartCondition()->getProcessesTerminated());
        static::assertCount(0, $process->getStartCondition()->getProcessesFailed());
    }
}
