<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\ProcessInterfaceCollection;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\ProcessInterface,
    Process\ProcessInterfaceCollection
};
use Steevanb\PhpCollection\{
    ObjectCollection\ComparisonModeEnum,
    ValueAlreadyExistsModeEnum
};

/** @covers \Steevanb\ParallelProcess\Process\ProcessInterfaceCollection::__construct */
final class ConstructTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $processes = new ProcessInterfaceCollection();

        static::assertCount(0, $processes);
        static::assertSame(ProcessInterface::class, $processes->getClassName());
        static::assertSame(ComparisonModeEnum::HASH->value, $processes->getComparisonMode()->value);
        static::assertSame(ValueAlreadyExistsModeEnum::ADD->value, $processes->getValueAlreadyExistsMode()->value);
    }
}
