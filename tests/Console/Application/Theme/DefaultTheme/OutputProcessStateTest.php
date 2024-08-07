<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\Theme\DefaultTheme;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Console\Application\Theme\DefaultTheme,
    Process\ProcessInterfaceCollection,
    Tests\Console\Output\TestOutput,
    Tests\CreateLsProcessTrait
};

/** @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::outputProcessesState */
final class OutputProcessStateTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testEmptyNotStarted(): void
    {
        $output = new TestOutput();
        (new DefaultTheme())->outputProcessesState(
            $output,
            new ProcessInterfaceCollection()
        );

        static::assertSame('', $output->getOutputed());
    }

    public function testEmptyNotStartedDecorated(): void
    {
        $output = new TestOutput();
        $output->setDecorated(true);

        (new DefaultTheme())->outputProcessesState(
            $output,
            new ProcessInterfaceCollection()
        );

        static::assertSame('', $output->getOutputed());
    }

    public function testNotStarted(): void
    {
        $output = new TestOutput();
        (new DefaultTheme())->outputProcessesState(
            $output,
            new ProcessInterfaceCollection([$this->createLsProcess()])
        );

        static::assertSame(
            "\e[1A\e[K> ls\n",
            $output->getOutputed()
        );
    }

    public function testNotStartedDecorated(): void
    {
        $output = new TestOutput();
        $output->setDecorated(true);

        (new DefaultTheme())->outputProcessesState(
            $output,
            new ProcessInterfaceCollection([$this->createLsProcess()])
        );

        static::assertSame(
            "\e[1A\e[K\e[37;45m > \e[39;49m ls\n",
            $output->getOutputed()
        );
    }

    public function testStarted(): void
    {
        $process1 = $this->createLsProcess();
        $process1->mustRun();

        $process2 = $this->createLsProcess();
        $process2->mustRun();

        $processes = new ProcessInterfaceCollection([$process1, $process2]);

        $output = new TestOutput();

        (new DefaultTheme())->outputProcessesState($output, $processes);

        static::assertSame(
            "\e[1A\e[K\e[1A\e[K✓ ls\n✓ ls\n",
            $output->getOutputed()
        );
    }

    public function testStartedDecorated(): void
    {
        $process1 = $this->createLsProcess();
        $process1->mustRun();

        $process2 = $this->createLsProcess();
        $process2->mustRun();

        $processes = new ProcessInterfaceCollection([$process1, $process2]);

        $output = new TestOutput();
        $output->setDecorated(true);

        (new DefaultTheme())->outputProcessesState($output, $processes);

        static::assertSame(
            "\e[1A\e[K\e[1A\e[K\e[37;42m ✓ \e[39;49m ls\n\e[37;42m ✓ \e[39;49m ls\n",
            $output->getOutputed()
        );
    }
}
