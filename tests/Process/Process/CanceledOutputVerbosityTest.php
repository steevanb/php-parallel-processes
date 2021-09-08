<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\Process;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Tests\CreateLsProcessTrait;

final class CanceledOutputVerbosityTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testSet(): void
    {
        $process = $this->createLsProcess();
        static::assertSame(64, $process->getCanceledOutputVerbosity());

        $process->setCanceledOutputVerbosity(32);
        static::assertSame(32, $process->getCanceledOutputVerbosity());
    }
}
