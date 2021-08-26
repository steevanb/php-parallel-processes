<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\ParallelProcessesApplication;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Console\Application\Theme\DefaultTheme
};

class ConstructorTest extends TestCase
{
    public function testSetTheme(): void
    {
        $application = new ParallelProcessesApplication();

        static::assertCount(0, $application->getProcesses());
        static::assertInstanceOf(DefaultTheme::class, $application->getTheme());
        static::assertSame(10000, $application->getRefreshInterval());
    }
}
