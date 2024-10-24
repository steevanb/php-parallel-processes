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
        static::assertCount(0, $startCondition->getProcessesSuccessful());
        static::assertCount(0, $startCondition->getProcessesFailed());
    }
}
