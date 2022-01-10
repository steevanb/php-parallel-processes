<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\Process;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Tests\CreateLsProcessTrait;

/**
 * @covers \Steevanb\ParallelProcess\Process\Process::__construct
 * @covers \Steevanb\ParallelProcess\Process\Process::setStandardOutputVerbosity
 * @covers \Steevanb\ParallelProcess\Process\Process::getStandardOutputVerbosity
 */
final class StandardOutputVerbosityTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testSet(): void
    {
        $process = $this->createLsProcess();
        static::assertSame(64, $process->getStandardOutputVerbosity());

        $process->setStandardOutputVerbosity(32);
        static::assertSame(32, $process->getStandardOutputVerbosity());
    }
}
