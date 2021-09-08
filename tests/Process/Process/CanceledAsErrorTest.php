<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\Process;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Tests\CreateLsProcessTrait;

final class CanceledAsErrorTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testSet(): void
    {
        $process = $this->createLsProcess();
        static::assertTrue($process->isCanceledAsError());

        $process->setCanceledAsError(false);
        static::assertFalse($process->isCanceledAsError());
    }

    public function testSetDefaultParameterValue(): void
    {
        $process = $this->createLsProcess();
        static::assertTrue($process->isCanceledAsError());

        $process->setCanceledAsError();
        static::assertTrue($process->isCanceledAsError());
    }
}
