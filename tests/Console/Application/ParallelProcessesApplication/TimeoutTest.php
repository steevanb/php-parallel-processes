<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\ParallelProcessesApplication;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process,
};
use Symfony\Component\Console\{
    Input\ArrayInput,
    Output\NullOutput
};

final class TimeoutTest extends TestCase
{
    /**
     * @covers \Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication::setTimeout
     * @covers \Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication::getTimeout
     */
    public function testAccessors(): void
    {
        $application = (new ParallelProcessesApplication())
            ->setTimeout(10);

        static::assertSame(10, $application->getTimeout());
    }

    /**
     * @covers \Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication::setTimeout
     * @covers \Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication::waitProcessesTermination
     */
    public function testTimeout(): void
    {
        $process = new Process(['sleep', '10'], dirname(__DIR__, 4));

        $time = time();

        $exitCode = (new ParallelProcessesApplication())
            ->setTimeout(1)
            ->setAutoExit(false)
            ->addProcess($process)
            ->run(new ArrayInput([]), new NullOutput());

        static::assertSame(1, $exitCode);
        static::assertTrue(time() >= $time + 1);
        static::assertTrue(time() <= $time + 2);
    }
}
