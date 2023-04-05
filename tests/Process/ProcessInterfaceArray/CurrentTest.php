<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\ProcessInterfaceArray;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\ProcessInterfaceArray,
    Tests\CreateLsProcessTrait
};

/**
 * @covers \Steevanb\ParallelProcess\Process\ProcessInterfaceArray::__construct
 * @covers \Steevanb\ParallelProcess\Process\ProcessInterfaceArray::next
 * @covers \Steevanb\ParallelProcess\Process\ProcessInterfaceArray::current
 */
final class CurrentTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testDefaultValues(): void
    {
        static::assertNull((new ProcessInterfaceArray())->current());
    }

    public function testOneItem(): void
    {
        $process = $this->createLsProcess();
        $processes = new ProcessInterfaceArray([$process]);

        static::assertSame($process, $processes->current());
        $processes->next();
        static::assertNull($processes->current());
    }
}
