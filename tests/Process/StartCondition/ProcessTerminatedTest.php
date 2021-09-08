<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\StartCondition;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\Process,
    Process\StartCondition
};

final class ProcessTerminatedTest extends TestCase
{
    public function testAdd(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['ls']);

        static::assertTrue($startCondition->getProcessesTerminated()->isReadOnly());
        $startCondition->addProcessTerminated($process);
        static::assertTrue($startCondition->getProcessesTerminated()->isReadOnly());

        static::assertCount(1, $startCondition->getProcessesTerminated());
        static::assertSame($process, $startCondition->getProcessesTerminated()[0]);
    }
}
