<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\StartCondition;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\ProcessArray,
    Process\StartCondition
};

final class ConstructTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $startCondition = new StartCondition();

        static::assertInstanceOf(ProcessArray::class, $startCondition->getProcessesTerminated());
        static::assertCount(0, $startCondition->getProcessesTerminated());
        static::assertTrue($startCondition->getProcessesTerminated()->isReadOnly());

        static::assertInstanceOf(ProcessArray::class, $startCondition->getProcessesSuccessful());
        static::assertCount(0, $startCondition->getProcessesSuccessful());
        static::assertTrue($startCondition->getProcessesSuccessful()->isReadOnly());

        static::assertInstanceOf(ProcessArray::class, $startCondition->getProcessesFailed());
        static::assertCount(0, $startCondition->getProcessesFailed());
        static::assertTrue($startCondition->getProcessesFailed()->isReadOnly());
    }
}
