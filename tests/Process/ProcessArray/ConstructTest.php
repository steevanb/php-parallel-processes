<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\ProcessArray;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\Process,
    Process\ProcessArray
};
use Steevanb\PhpTypedArray\{
    NullValueModeEnum,
    ObjectComparisonModeEnum,
    ValueAlreadyExistsModeEnum
};

/** @covers \Steevanb\ParallelProcess\Process\ProcessArray::__construct */
final class ConstructTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $processes = new ProcessArray();

        static::assertCount(0, $processes);
        static::assertSame(Process::class, $processes->getClassName());
        static::assertSame(ObjectComparisonModeEnum::STRING, $processes->getComparisonMode());
        static::assertSame(ValueAlreadyExistsModeEnum::ADD, $processes->getValueAlreadyExistMode());
        static::assertSame(NullValueModeEnum::ALLOW, $processes->getNullValueMode());
    }
}
