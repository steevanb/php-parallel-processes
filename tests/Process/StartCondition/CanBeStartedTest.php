<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\StartCondition;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\Process,
    Process\StartCondition,
    Tests\CreateLsProcessTrait
};

/**
 * @covers \Steevanb\ParallelProcess\Process\StartCondition::__construct
 * @covers \Steevanb\ParallelProcess\Process\StartCondition::canBeStarted
 * @covers \Steevanb\ParallelProcess\Process\StartCondition::getProcessesTerminated
 * @covers \Steevanb\ParallelProcess\Process\StartCondition::getProcessesSuccessful
 * @covers \Steevanb\ParallelProcess\Process\StartCondition::getProcessesFailed
 */
final class CanBeStartedTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testCanBeStarted(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());

        static::assertTrue($startCondition->canBeStarted());
    }

    public function testHaveOneNotTerminated(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = $this->createLsProcess();
        $startCondition->getProcessesTerminated()->add($process);

        static::assertFalse($process->isTerminated());
        static::assertFalse($startCondition->canBeStarted());
    }

    public function testHaveOneTerminated(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = $this->createLsProcess();
        $process->run();
        $startCondition->getProcessesTerminated()->add($process);

        static::assertTrue($process->isTerminated());
        static::assertTrue($startCondition->canBeStarted());
    }

    public function testHaveOneNotSuccessful(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = $this->createLsProcess();
        $startCondition->getProcessesSuccessful()->add($process);

        static::assertFalse($process->isTerminated());
        static::assertFalse($startCondition->canBeStarted());
    }

    public function testHaveOneSuccessful(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = $this->createLsProcess();
        $process->mustRun();
        $startCondition->getProcessesSuccessful()->add($process);

        static::assertTrue($process->isTerminated());
        static::assertTrue($process->isSuccessful());
        static::assertTrue($startCondition->canBeStarted());
    }

    public function testHaveOneNotFailed(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = new Process(['unknown-command']);
        $startCondition->getProcessesFailed()->add($process);

        static::assertFalse($process->isTerminated());
        static::assertFalse($startCondition->canBeStarted());
    }

    public function testHaveOneFailed(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = new Process(['unknown-command']);
        $process->run();
        $startCondition->getProcessesFailed()->add($process);

        static::assertTrue($process->isTerminated());
        static::assertFalse($process->isSuccessful());
        static::assertTrue($startCondition->canBeStarted());
    }
}
