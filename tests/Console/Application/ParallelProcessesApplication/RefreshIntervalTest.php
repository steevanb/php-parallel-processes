<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\ParallelProcessesApplication;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication;

final class RefreshIntervalTest extends TestCase
{
    public function testSetRefreshInterval(): void
    {
        $application = new ParallelProcessesApplication();

        static::assertSame(10000, $application->getRefreshInterval());

        $application->setRefreshInterval(12);

        static::assertSame(12, $application->getRefreshInterval());
    }
}
