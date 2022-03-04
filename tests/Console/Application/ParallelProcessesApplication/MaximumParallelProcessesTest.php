<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\ParallelProcessesApplication;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication;

/**
 * @covers \Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication::setMaximumParallelProcesses
 * @covers \Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication::getMaximumParallelProcesses
 */
final class MaximumParallelProcessesTest extends TestCase
{
    public function testSetMaximumParallelProcesses(): void
    {
        $application = (new ParallelProcessesApplication())
            ->setMaximumParallelProcesses(42);

        static::assertSame(42, $application->getMaximumParallelProcesses());
    }
}
