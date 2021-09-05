<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\ProcessArray;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\Process,
    Process\ProcessArray
};

final class GetReadyTest extends TestCase
{
    public function testReady(): void
    {
        $process1 = new Process(['ls']);
        $process2 = new Process(['pwd']);
        $processes = new ProcessArray([$process1, $process2]);

        $process2->run();

        static::assertCount(1, $processes->getReady());
        static::assertSame($process1, $processes->getReady()[0]);
    }
}
