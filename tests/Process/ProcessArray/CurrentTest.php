<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\ProcessArray;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\Process,
    Process\ProcessArray,
    Tests\CreateLsProcessTrait
};

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
