<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\ParallelProcessesApplication;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication;

/**
 * @covers \Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication::setRefreshInterval
 * @covers \Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication::getRefreshInterval
 */
final class RefreshIntervalTest extends TestCase
{
    public function testSetRefreshInterval(): void
    {
        $application = (new ParallelProcessesApplication())
            ->setRefreshInterval(42);

        static::assertSame(42, $application->getRefreshInterval());
    }
}
