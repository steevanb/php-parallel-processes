<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\Theme\SummaryTheme;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Console\Application\Theme\SummaryTheme,
    Process\ProcessInterfaceCollection,
    Tests\Console\Output\TestOutput,
    Tests\CreateLsProcessTrait
};

/** @covers \Steevanb\ParallelProcess\Console\Application\Theme\SummaryTheme::outputProcessesState */
final class OutputProcessStateTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testEmptyNotStarted(): void
    {
        $output = new TestOutput();
        (new SummaryTheme())->outputProcessesState(
            $output,
            new ProcessInterfaceCollection()
        );

        static::assertSame('', $output->getOutputed());
    }

    public function testNotStarted(): void
    {
        $output = new TestOutput();
        (new SummaryTheme())->outputProcessesState(
            $output,
            new ProcessInterfaceCollection([$this->createLsProcess()])
        );

        static::assertSame('', $output->getOutputed());
    }

    public function testStarted(): void
    {
        $process1 = $this->createLsProcess();
        $process1->mustRun();

        $process2 = $this->createLsProcess();
        $process2->mustRun();

        $processes = new ProcessInterfaceCollection([$process1, $process2]);

        $output = new TestOutput();

        (new SummaryTheme())->outputProcessesState($output, $processes);

        static::assertSame('', $output->getOutputed());
    }
}
