<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\Theme\DefaultTheme;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Console\Application\Theme\DefaultTheme,
    Process\ProcessArray,
    Tests\Console\Output\TestOutput,
    Tests\CreateLsProcessTrait
};

final class ResetOutputTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testEmptyNotStarted(): void
    {
        $output = new TestOutput();
        (new DefaultTheme())->resetOutput(
            $output,
            new ProcessArray()
        );

        static::assertSame('', $output->getOutputed());
    }

    public function testNotStarted(): void
    {
        (new DefaultTheme())->resetOutput(
            new TestOutput(),
            new ProcessArray([$this->createLsProcess()])
        );
    }

    public function testStarted(): void
    {
        $process1 = $this->createLsProcess();
        $process1->start();

        $process2 = $this->createLsProcess();
        $process2->start();

        $processes = new ProcessArray([$process1, $process2]);

        $output = new TestOutput();

        (new DefaultTheme())->resetOutput($output, $processes);

        static::assertSame("\e[1A\e[K\e[1A\e[K", $output->getOutputed());
    }
}
