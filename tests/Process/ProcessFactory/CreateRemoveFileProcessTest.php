<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\ProcessFactory;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process,
    Process\ProcessFactory
};

/** @covers \Steevanb\ParallelProcess\Process\ProcessFactory::createRemoveFileProcess */
final class CreateRemoveFileProcessTest extends TestCase
{
    public function testFileExists(): void
    {
        $application = new ParallelProcessesApplication();

        /** @var Process $process */
        $process = ProcessFactory::createRemoveFileProcess(__FILE__, $application);

        static::assertInstanceOf(Process::class, $process);
        static::assertTrue($application->hasProcess($process));
    }

    public function testFileNotFound(): void
    {
        $application = new ParallelProcessesApplication();

        $process = ProcessFactory::createRemoveFileProcess(__FILE__ . '.foo.bar', $application);

        static::assertNull($process);
    }

    public function testName(): void
    {
        $application = new ParallelProcessesApplication();

        /** @var Process $process */
        $process = ProcessFactory::createRemoveFileProcess(__FILE__, $application, 'foo');

        static::assertInstanceOf(Process::class, $process);
        static::assertSame('foo', $process->getName());
    }

    public function testCwd(): void
    {
        $application = new ParallelProcessesApplication();

        /** @var Process $process */
        $process = ProcessFactory::createRemoveFileProcess(__FILE__, $application, 'foo', '/foo/bar');

        static::assertInstanceOf(Process::class, $process);
        static::assertSame('/foo/bar', $process->getWorkingDirectory());
    }
}
