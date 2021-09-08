<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\ProcessArray;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\ProcessArray,
    Tests\CreateLsProcessTrait
};

final class GetReadyTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testReady(): void
    {
        $process1 = $this->createLsProcess();
        $process2 = $this->createLsProcess();
        $processes = new ProcessArray([$process1, $process2]);

        $process2->run();

        static::assertCount(1, $processes->getReady());
        static::assertSame($process1, $processes->getReady()[0]);
    }
}
