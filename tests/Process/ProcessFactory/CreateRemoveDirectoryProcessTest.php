<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\ProcessFactory;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process,
    Process\ProcessFactory
};

/** @covers \Steevanb\ParallelProcess\Process\ProcessFactory::createRemoveDirectoryProcess */
class CreateRemoveDirectoryProcessTest extends TestCase
{
    public function testDirectoryExists(): void
    {
        $application = new ParallelProcessesApplication();

        /** @var Process $process */
        $process = ProcessFactory::createRemoveDirectoryProcess(__DIR__, $application);

        static::assertInstanceOf(Process::class, $process);
        static::assertTrue($application->hasProcess($process));
    }

    public function testDirectoryNotFound(): void
    {
        $application = new ParallelProcessesApplication();

        $process = ProcessFactory::createRemoveDirectoryProcess(__DIR__ . '/foo/bar', $application);

        static::assertNull($process);
    }

    public function testName(): void
    {
        $application = new ParallelProcessesApplication();

        /** @var Process $process */
        $process = ProcessFactory::createRemoveDirectoryProcess(__DIR__, $application, 'foo');

        static::assertInstanceOf(Process::class, $process);
        static::assertSame('foo', $process->getName());
    }

    public function testCwd(): void
    {
        $application = new ParallelProcessesApplication();

        /** @var Process $process */
        $process = ProcessFactory::createRemoveDirectoryProcess(__DIR__, $application, 'foo', '/foo/bar');

        static::assertInstanceOf(Process::class, $process);
        static::assertSame('/foo/bar', $process->getWorkingDirectory());
    }
}
