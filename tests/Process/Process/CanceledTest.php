<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\Process;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Tests\CreateLsProcessTrait;

final class CanceledTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testSet(): void
    {
        $process = $this->createLsProcess();
        static::assertFalse($process->isCanceled());

        $process->setCanceled(true);
        static::assertTrue($process->isCanceled());
    }

    public function testSetDefaultParameterValue(): void
    {
        $process = $this->createLsProcess();
        static::assertFalse($process->isCanceled());

        $process->setCanceled();
        static::assertTrue($process->isCanceled());
    }
}
