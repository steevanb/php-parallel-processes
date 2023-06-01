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
 * @covers \Steevanb\ParallelProcess\Process\StartCondition::hasConditions
 */
final class HasConditionsTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testDontHave(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());

        static::assertFalse($startCondition->hasConditions());
    }

    public function testHaveOneTerminated(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = $this->createLsProcess();
        $startCondition->addProcessTerminated($process);

        static::assertTrue($startCondition->hasConditions());
    }

    public function testHaveTwoTerminated(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = $this->createLsProcess();
        $startCondition->addProcessTerminated($process);
        $startCondition->addProcessTerminated($process);

        static::assertTrue($startCondition->hasConditions());
    }

    public function testHaveOneSuccessful(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = $this->createLsProcess();
        $startCondition->addProcessSuccessful($process);

        static::assertTrue($startCondition->hasConditions());
    }

    public function testHaveTwoSuccessful(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = $this->createLsProcess();
        $startCondition->addProcessSuccessful($process);
        $startCondition->addProcessSuccessful($process);

        static::assertTrue($startCondition->hasConditions());
    }

    public function testHaveOneFailed(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = $this->createLsProcess();
        $startCondition->addProcessFailed($process);

        static::assertTrue($startCondition->hasConditions());
    }

    public function testHaveTwoFailed(): void
    {
        $startCondition = new StartCondition($this->createLsProcess());
        $process = $this->createLsProcess();
        $startCondition->addProcessFailed($process);
        $startCondition->addProcessFailed($process);

        static::assertTrue($startCondition->hasConditions());
    }
}
