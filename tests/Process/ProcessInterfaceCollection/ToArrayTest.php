<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\ProcessInterfaceCollection;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\ProcessInterfaceCollection,
    Tests\CreateLsProcessTrait
};

/** @covers \Steevanb\ParallelProcess\Process\ProcessInterfaceCollection::toArray */
final class ToArrayTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testDefaultValues(): void
    {
        static::assertCount(0, (new ProcessInterfaceCollection())->toArray());
    }

    public function testOneItem(): void
    {
        $process = $this->createLsProcess();
        $processes = new ProcessInterfaceCollection([$process]);

        static::assertCount(1, $processes->toArray());
        static::assertSame($process, $processes->toArray()[0]);
    }
}
