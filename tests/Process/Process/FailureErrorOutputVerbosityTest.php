<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\Process;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Tests\CreateLsProcessTrait;

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
