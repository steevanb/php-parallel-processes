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
 * @covers \Steevanb\ParallelProcess\Process\StartCondition::isCanceled
 */
final class IsCanceledTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testIsCanceled(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());

        static::assertFalse($startCondition->isCanceled());
    }

    public function testHaveOneNotStartedNotSuccessful(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = $this->createLsProcess();
        $startCondition->getProcessesSuccessful()->add($process);

        static::assertFalse($process->isSuccessful());
        static::assertFalse($startCondition->isCanceled());
    }

    public function testHaveOneSuccessful(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = $this->createLsProcess();
        $process->mustRun();
        $startCondition->getProcessesSuccessful()->add($process);

        static::assertTrue($process->isSuccessful());
        static::assertFalse($startCondition->isCanceled());
    }

    public function testHaveOneNotSuccessful(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = new Process(['unknown-command']);
        $process->run();
        $startCondition->getProcessesSuccessful()->add($process);

        static::assertFalse($process->isSuccessful());
        static::assertTrue($startCondition->isCanceled());
    }

    public function testHaveOneNotStartedNotFailed(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = new Process(['unknown-command']);
        $startCondition->getProcessesFailed()->add($process);

        static::assertFalse($process->isSuccessful());
        static::assertFalse($startCondition->isCanceled());
    }

    public function testHaveOneFailed(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = new Process(['unknown-command']);
        $process->run();
        $startCondition->getProcessesFailed()->add($process);

        static::assertFalse($process->isSuccessful());
        static::assertFalse($startCondition->isCanceled());
    }

    public function testHaveOneNotFailed(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = $this->createLsProcess();
        $process->mustRun();
        $startCondition->getProcessesFailed()->add($process);

        static::assertTrue($process->isSuccessful());
        static::assertTrue($startCondition->isCanceled());
    }
}
