<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\StartCondition;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\StartCondition,
    Tests\CreateLsProcessTrait
};

/** @covers \Steevanb\ParallelProcess\Process\StartCondition::__construct */
final class ConstructTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testDefaultValues(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());

        static::assertCount(0, $startCondition->getProcessesTerminated());
        static::assertTrue($startCondition->getProcessesTerminated()->isReadOnly());

        static::assertCount(0, $startCondition->getProcessesSuccessful());
        static::assertTrue($startCondition->getProcessesSuccessful()->isReadOnly());

        static::assertCount(0, $startCondition->getProcessesFailed());
        static::assertTrue($startCondition->getProcessesFailed()->isReadOnly());
    }
}
