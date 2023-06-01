<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\Process;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Tests\CreateLsProcessTrait;

/**
 * @covers \Steevanb\ParallelProcess\Process\Process::__construct
 * @covers \Steevanb\ParallelProcess\Process\Process::setErrorOutputVerbosity
 * @covers \Steevanb\ParallelProcess\Process\Process::getErrorOutputVerbosity
 */
final class ErrorOutputVerbosityTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testSet(): void
    {
        $process = $this->createLsProcess();
        static::assertSame(128, $process->getErrorOutputVerbosity());

        $process->setErrorOutputVerbosity(32);
        static::assertSame(32, $process->getErrorOutputVerbosity());
    }
}
