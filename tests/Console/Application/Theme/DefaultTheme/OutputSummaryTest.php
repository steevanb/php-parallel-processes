<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\Theme\DefaultTheme;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Console\Application\Theme\DefaultTheme,
    Process\Process,
    Process\ProcessArray,
    Tests\Console\Output\TestOutput
};
use Symfony\Component\Process\Exception\LogicException;

final class OutputSummaryTest extends TestCase
{
    public function testEmptyNotStarted(): void
    {
        $output = new TestOutput();
        (new DefaultTheme())->outputSummary(
            $output,
            new ProcessArray()
        );

        static::assertSame('', $output->getOutputed());
    }

    public function testNotStarted(): void
    {
        $this->expectException(LogicException::class);

        (new DefaultTheme())->outputSummary(
            new TestOutput(),
            new ProcessArray(new Process(['ls']))
        );
    }

    public function testStarted(): void
    {
        $process1 = new Process(['ls']);
        $process1->mustRun();

        $process2 = new Process(['ls']);
        $process2->mustRun();

        $processes = new ProcessArray([$process1, $process2]);

        $output = new TestOutput();

        (new DefaultTheme())->outputSummary($output, $processes);

        static::assertSame(
            "\e[37;42m ✓ \e[39;49m ls\n\e[37;42m ✓ \e[39;49m ls\n",
            $output->getOutputed()
        );
    }
}
