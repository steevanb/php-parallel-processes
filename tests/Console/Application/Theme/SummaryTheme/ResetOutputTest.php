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

/** @covers \Steevanb\ParallelProcess\Console\Application\Theme\SummaryTheme::resetOutput */
final class ResetOutputTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testEmptyNotStarted(): void
    {
        $output = new TestOutput();
        $theme = new SummaryTheme();
        $result = $theme->resetOutput(
            $output,
            new ProcessInterfaceCollection()
        );

        static::assertSame($theme, $result);
        static::assertSame('', $output->getOutputed());
    }

    public function testNotStarted(): void
    {
        $processes = new ProcessInterfaceCollection([$this->createLsProcess(), $this->createLsProcess()]);
        $output = new TestOutput();
        $theme = new SummaryTheme();
        $result = $theme->resetOutput($output, $processes);

        static::assertSame($theme, $result);
        static::assertSame('', $output->getOutputed());
    }

    public function testStarted(): void
    {
        $process1 = $this->createLsProcess();
        $process1->start();

        $process2 = $this->createLsProcess();
        $process2->start();

        $processes = new ProcessInterfaceCollection([$process1, $process2]);
        $output = new TestOutput();
        $theme = new SummaryTheme();
        $result = $theme->resetOutput($output, $processes);

        static::assertSame($theme, $result);
        static::assertSame('', $output->getOutputed());
    }
}
