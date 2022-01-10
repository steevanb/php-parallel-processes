<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\Process;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Tests\CreateLsProcessTrait;

/**
 * @covers \Steevanb\ParallelProcess\Process\Process::__construct
 * @covers \Steevanb\ParallelProcess\Process\Process::setFailureStandardOutputVerbosity
 * @covers \Steevanb\ParallelProcess\Process\Process::getFailureStandardOutputVerbosity
 */
final class FailureStandardOutputVerbosityTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testSet(): void
    {
        $process = $this->createLsProcess();
        static::assertSame(32, $process->getFailureStandardOutputVerbosity());

        $process->setFailureStandardOutputVerbosity(64);
        static::assertSame(64, $process->getFailureStandardOutputVerbosity());
    }
}
