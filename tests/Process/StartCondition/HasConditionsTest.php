<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\StartCondition;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\Process,
    Process\StartCondition
};

final class HasConditionsTest extends TestCase
{
    public function testDontHave(): void
    {
        $startCondition = new StartCondition();

        static::assertFalse($startCondition->hasConditions());
    }

    public function testHaveOneTerminated(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['ls']);
        $startCondition->addProcessTerminated($process);

        static::assertTrue($startCondition->hasConditions());
    }

    public function testHaveTwoTerminated(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['ls']);
        $startCondition->addProcessTerminated($process);
        $startCondition->addProcessTerminated($process);

        static::assertTrue($startCondition->hasConditions());
    }

    public function testHaveOneSuccessful(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['ls']);
        $startCondition->addProcessSuccessful($process);

        static::assertTrue($startCondition->hasConditions());
    }

    public function testHaveTwoSuccessful(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['ls']);
        $startCondition->addProcessSuccessful($process);
        $startCondition->addProcessSuccessful($process);

        static::assertTrue($startCondition->hasConditions());
    }

    public function testHaveOneFailed(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['ls']);
        $startCondition->addProcessFailed($process);

        static::assertTrue($startCondition->hasConditions());
    }

    public function testHaveTwoFailed(): void
    {
        $startCondition = new StartCondition();
        $process = new Process(['ls']);
        $startCondition->addProcessFailed($process);
        $startCondition->addProcessFailed($process);

        static::assertTrue($startCondition->hasConditions());
    }
}
