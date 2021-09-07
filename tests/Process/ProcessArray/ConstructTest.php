<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\ProcessArray;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\Process,
    Process\ProcessArray
};

final class ConstructTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $processes = new ProcessArray();

        static::assertCount(0, $processes);
        static::assertSame(Process::class, $processes->getClassName());
        static::assertSame(ProcessArray::COMPARISON_STRING, $processes->getComparisonMode());
        static::assertSame(ProcessArray::VALUE_ALREADY_EXIST_ADD, $processes->getValueAlreadyExistMode());
        static::assertSame(ProcessArray::NULL_VALUE_ALLOW, $processes->getNullValueMode());
    }
}
