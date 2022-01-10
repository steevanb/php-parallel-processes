<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\ProcessArray;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\ProcessArray,
    Tests\CreateLsProcessTrait
};

/**
 * @covers \Steevanb\ParallelProcess\Process\ProcessArray::__construct
 * @covers \Steevanb\ParallelProcess\Process\ProcessArray::next
 * @covers \Steevanb\ParallelProcess\Process\ProcessArray::current
 */
final class CurrentTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testDefaultValues(): void
    {
        static::assertNull((new ProcessArray())->current());
    }

    public function testOneItem(): void
    {
        $process = $this->createLsProcess();
        $processes = new ProcessArray([$process]);

        static::assertSame($process, $processes->current());
        $processes->next();
        static::assertNull($processes->current());
    }
}
