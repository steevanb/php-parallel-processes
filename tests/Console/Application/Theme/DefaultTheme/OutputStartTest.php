<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\Theme\DefaultTheme;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Console\Application\Theme\DefaultTheme,
    Process\ProcessInterfaceArray,
    Tests\Console\Output\TestOutput,
    Tests\CreateLsProcessTrait
};

/** @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::outputStart */
final class OutputStartTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testEmptyNotStarted(): void
    {
        $output = new TestOutput();
        (new DefaultTheme())->outputStart(
            $output,
            new ProcessInterfaceArray()
        );

        static::assertSame('', $output->getOutputed());
    }

    public function testEmptyNotStartedDecorated(): void
    {
        $output = (new TestOutput())->setDecorated(true);
        (new DefaultTheme())->outputStart(
            $output,
            new ProcessInterfaceArray()
        );

        static::assertSame('', $output->getOutputed());
    }

    public function testNotStarted(): void
    {
        $output = new TestOutput();
        (new DefaultTheme())->outputStart(
            $output,
            new ProcessInterfaceArray([$this->createLsProcess()])
        );

        static::assertSame(
            "> ls\n",
            $output->getOutputed()
        );
    }

    public function testNotStartedDecorated(): void
    {
        $output = (new TestOutput())->setDecorated(true);
        (new DefaultTheme())->outputStart(
            $output,
            new ProcessInterfaceArray([$this->createLsProcess()])
        );

        static::assertSame(
            "\e[37;45m > \e[39;49m ls\n",
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

        (new DefaultTheme())->outputStart($output, $processes);

        static::assertSame(
            "✓ ls\n✓ ls\n",
            $output->getOutputed()
        );
    }

    public function testStartedDecorated(): void
    {
        $process1 = $this->createLsProcess();
        $process1->mustRun();

        $process2 = $this->createLsProcess();
        $process2->mustRun();

        $processes = new ProcessInterfaceArray([$process1, $process2]);

        $output = (new TestOutput())->setDecorated(true);

        (new DefaultTheme())->outputStart($output, $processes);

        static::assertSame(
            "\e[37;42m ✓ \e[39;49m ls\n\e[37;42m ✓ \e[39;49m ls\n",
            $output->getOutputed()
        );
    }
}
