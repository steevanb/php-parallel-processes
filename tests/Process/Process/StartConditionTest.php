<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\Process;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\StartCondition,
    Tests\CreateLsProcessTrait
};

/**
 * @covers \Steevanb\ParallelProcess\Process\Process::__construct
 * @covers \Steevanb\ParallelProcess\Process\Process::getStartCondition
 */
final class StartConditionTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testGet(): void
    {
        static::assertInstanceOf(StartCondition::class, $this->createLsProcess()->getStartCondition());
    }
}
