<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\ProcessInterfaceArray;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\ProcessInterface,
    Process\ProcessInterfaceArray
};
use Steevanb\PhpTypedArray\{
    NullValueModeEnum,
    ObjectComparisonModeEnum,
    ValueAlreadyExistsModeEnum
};

/** @covers \Steevanb\ParallelProcess\Process\ProcessInterfaceArray::__construct */
final class ConstructTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $processes = new ProcessInterfaceArray();

        static::assertCount(0, $processes);
        static::assertSame(ProcessInterface::class, $processes->getClassName());
        static::assertSame(ObjectComparisonModeEnum::STRING, $processes->getComparisonMode());
        static::assertSame(ValueAlreadyExistsModeEnum::ADD, $processes->getValueAlreadyExistMode());
        static::assertSame(NullValueModeEnum::ALLOW, $processes->getNullValueMode());
    }
}
