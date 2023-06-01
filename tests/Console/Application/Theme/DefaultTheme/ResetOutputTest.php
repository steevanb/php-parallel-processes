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

/** @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::resetOutput */
final class ResetOutputTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testEmptyNotStarted(): void
    {
        $output = new TestOutput();
        (new DefaultTheme())->resetOutput(
            $output,
            new ProcessInterfaceCollection()
        );

        static::assertSame('', $output->getOutputed());
    }

    public function testNotStarted(): void
    {
        $processes = new ProcessInterfaceCollection([$this->createLsProcess(), $this->createLsProcess()]);
        $output = new TestOutput();

        (new DefaultTheme())->resetOutput($output, $processes);

        static::assertSame("\e[1A\e[K\e[1A\e[K", $output->getOutputed());
    }

    public function testStarted(): void
    {
        $process1 = $this->createLsProcess();
        $process1->start();

        $process2 = $this->createLsProcess();
        $process2->start();

        $processes = new ProcessInterfaceCollection([$process1, $process2]);

        $output = new TestOutput();

        (new DefaultTheme())->resetOutput($output, $processes);

        static::assertSame("\e[1A\e[K\e[1A\e[K", $output->getOutputed());
    }
}
