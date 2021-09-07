<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\ProcessArray;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\Process,
    Process\ProcessArray
};

final class ToArrayTest extends TestCase
{
    public function testDefaultValues(): void
    {
        static::assertCount(0, (new ProcessArray())->toArray());
    }

    public function testOneItem(): void
    {
        $process = new Process(['ls']);
        $processes = new ProcessArray([$process]);

        static::assertCount(1, $processes->toArray());
        static::assertSame($process, $processes->toArray()[0]);
    }
}
