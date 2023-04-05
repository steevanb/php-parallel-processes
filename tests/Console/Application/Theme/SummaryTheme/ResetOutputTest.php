<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\Theme\SummaryTheme;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Console\Application\Theme\SummaryTheme,
    Process\ProcessInterfaceArray,
    Tests\Console\Output\TestOutput,
    Tests\CreateLsProcessTrait
};

/** @covers \Steevanb\ParallelProcess\Console\Application\Theme\SummaryTheme::resetOutput */
final class ResetOutputTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testEmptyNotStarted(): void
    {
        $output = new TestOutput();
        (new SummaryTheme())->resetOutput(
            $output,
            new ProcessInterfaceArray()
        );

        static::assertSame('', $output->getOutputed());
    }

    public function testNotStarted(): void
    {
        $processes = new ProcessInterfaceArray([$this->createLsProcess(), $this->createLsProcess()]);
        $output = new TestOutput();

        (new SummaryTheme())->resetOutput($output, $processes);

        static::assertSame('', $output->getOutputed());
    }

    public function testStarted(): void
    {
        $process1 = $this->createLsProcess();
        $process1->start();

        $process2 = $this->createLsProcess();
        $process2->start();

        $processes = new ProcessInterfaceArray([$process1, $process2]);

        $output = new TestOutput();

        (new SummaryTheme())->resetOutput($output, $processes);

        static::assertSame('', $output->getOutputed());
    }
}
