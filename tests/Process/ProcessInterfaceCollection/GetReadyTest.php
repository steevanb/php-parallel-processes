<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\ProcessInterfaceCollection;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\ProcessInterfaceCollection,
    Tests\CreateLsProcessTrait
};

/** @covers \Steevanb\ParallelProcess\Process\ProcessInterfaceCollection::getReady */
final class GetReadyTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testReady(): void
    {
        $process1 = $this->createLsProcess();
        $process2 = $this->createLsProcess();
        $processes = new ProcessInterfaceCollection([$process1, $process2]);

        $process2->run();

        static::assertCount(1, $processes->getReady());
        static::assertSame($process1, $processes->getReady()->get(0));
    }
}
