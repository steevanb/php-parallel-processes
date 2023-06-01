<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\StartCondition;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\StartCondition,
    Tests\CreateLsProcessTrait
};

/**
 * @covers \Steevanb\ParallelProcess\Process\StartCondition::__construct
 * @covers \Steevanb\ParallelProcess\Process\StartCondition::getProcessesFailed
 */
final class ProcessFailedTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testAdd(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = $this->createLsProcess();

        static::assertTrue($startCondition->getProcessesFailed()->isReadOnly());
        $startCondition->addProcessFailed($process);
        static::assertTrue($startCondition->getProcessesFailed()->isReadOnly());

        static::assertCount(1, $startCondition->getProcessesFailed());
        static::assertSame($process, $startCondition->getProcessesFailed()->get(0));
    }
}
