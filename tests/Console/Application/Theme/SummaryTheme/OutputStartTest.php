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

/** @covers \Steevanb\ParallelProcess\Console\Application\Theme\SummaryTheme::outputStart */
final class OutputStartTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testEmptyNotStarted(): void
    {
        $output = new TestOutput();
        (new SummaryTheme())->outputStart(
            $output,
            new ProcessInterfaceArray()
        );

        static::assertSame("Starting <info>0</info> processes...\n", $output->getOutputed());
    }

    public function testNotStarted(): void
    {
        $output = new TestOutput();
        (new SummaryTheme())->outputStart(
            $output,
            new ProcessInterfaceArray([$this->createLsProcess()])
        );

        static::assertSame(
            "Starting <info>1</info> process...\n",
            $output->getOutputed()
        );
    }

    public function testStarted(): void
    {
        $process1 = $this->createLsProcess();
        $process1->mustRun();

        $process2 = $this->createLsProcess();
        $process2->mustRun();

        $processes = new ProcessInterfaceArray([$process1, $process2]);

        $output = new TestOutput();

        (new SummaryTheme())->outputStart($output, $processes);

        static::assertSame(
            "Starting <info>2</info> processes...\n",
            $output->getOutputed()
        );
    }
}
