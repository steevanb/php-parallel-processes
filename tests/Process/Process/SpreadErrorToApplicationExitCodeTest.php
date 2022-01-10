<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\Process;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Tests\CreateLsProcessTrait;

/**
 * @covers \Steevanb\ParallelProcess\Process\Process::__construct
 * @covers \Steevanb\ParallelProcess\Process\Process::setSpreadErrorToApplicationExitCode
 * @covers \Steevanb\ParallelProcess\Process\Process::isSpreadErrorToApplicationExitCode
 */
final class SpreadErrorToApplicationExitCodeTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testSet(): void
    {
        $process = $this->createLsProcess();
        static::assertTrue($process->isSpreadErrorToApplicationExitCode());

        $process->setSpreadErrorToApplicationExitCode(false);
        static::assertFalse($process->isSpreadErrorToApplicationExitCode());
    }

    public function testSetDefaultParameterValue(): void
    {
        $process = $this->createLsProcess();
        static::assertTrue($process->isSpreadErrorToApplicationExitCode());

        $process->setSpreadErrorToApplicationExitCode();
        static::assertTrue($process->isSpreadErrorToApplicationExitCode());
    }
}
