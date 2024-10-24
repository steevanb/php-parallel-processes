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
 * @covers \Steevanb\ParallelProcess\Process\StartCondition::getProcessesSuccessful
 */
final class ProcessSuccessfulTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testAdd(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = $this->createLsProcess();
        $startCondition->getProcessesSuccessful()->add($process);

        static::assertCount(1, $startCondition->getProcessesSuccessful());
        static::assertSame($process, $startCondition->getProcessesSuccessful()->get(0));
    }
}
