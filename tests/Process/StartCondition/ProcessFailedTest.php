<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\StartCondition;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\Process,
    Process\StartCondition
};

final class ProcessFailedTest extends TestCase
{
    public function testAdd(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['ls']);

        static::assertTrue($startCondition->getProcessesFailed()->isReadOnly());
        $startCondition->addProcessFailed($process);
        static::assertTrue($startCondition->getProcessesFailed()->isReadOnly());

        static::assertCount(1, $startCondition->getProcessesFailed());
        static::assertSame($process, $startCondition->getProcessesFailed()[0]);
    }
}
