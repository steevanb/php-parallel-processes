<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\ParallelProcessesApplication;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Tests\CreateSleepProcessTrait
};

/** @covers \Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication::getSubscribedSignals */
final class GetSubscribedSignalsTest extends TestCase
{
    use CreateSleepProcessTrait;

    public function testDefaultValue(): void
    {
        $application = new ParallelProcessesApplication();

        static::assertCount(1, $application->getSubscribedSignals());
        static::assertSame(SIGINT, $application->getSubscribedSignals()[0]);
    }
}
