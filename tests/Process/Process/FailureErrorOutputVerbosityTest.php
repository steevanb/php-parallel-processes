<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\Process;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Tests\CreateLsProcessTrait;

/**
 * @covers \Steevanb\ParallelProcess\Process\Process::__construct
 * @covers \Steevanb\ParallelProcess\Process\Process::setFailureErrorOutputVerbosity
 * @covers \Steevanb\ParallelProcess\Process\Process::getFailureErrorOutputVerbosity
 */
final class FailureErrorOutputVerbosityTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testSet(): void
    {
        $process = $this->createLsProcess();
        static::assertSame(32, $process->getFailureErrorOutputVerbosity());

        $process->setFailureErrorOutputVerbosity(64);
        static::assertSame(64, $process->getFailureErrorOutputVerbosity());
    }
}
