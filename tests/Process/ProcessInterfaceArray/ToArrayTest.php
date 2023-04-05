<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\ProcessInterfaceArray;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\ProcessInterfaceArray,
    Tests\CreateLsProcessTrait
};

/** @covers \Steevanb\ParallelProcess\Process\ProcessInterfaceArray::toArray */
final class ToArrayTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testDefaultValues(): void
    {
        static::assertCount(0, (new ProcessInterfaceArray())->toArray());
    }

    public function testOneItem(): void
    {
        $process = $this->createLsProcess();
        $processes = new ProcessInterfaceArray([$process]);

        static::assertCount(1, $processes->toArray());
        static::assertSame($process, $processes->toArray()[0]);
    }
}
