<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\Theme\DefaultTheme;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Console\Application\Theme\DefaultTheme,
    Exception\ParallelProcessException,
    Process\ProcessArray,
    Tests\Console\Output\TestOutput,
    Tests\CreateLsProcessTrait
};

final class OutputSummaryTest extends TestCase
{
    use CreateLsProcessTrait;

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
        $this->expectException(ParallelProcessException::class);
        $this->expectExceptionMessage('Unknown process state.');
        $this->expectExceptionCode(0);

        (new DefaultTheme())->outputSummary(
            new TestOutput(),
            new ProcessArray([$this->createLsProcess()])
        );
    }

    public function testStarted(): void
    {
        $process1 = $this->createLsProcess();
        $process1->mustRun();

        $process2 = $this->createLsProcess();
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
