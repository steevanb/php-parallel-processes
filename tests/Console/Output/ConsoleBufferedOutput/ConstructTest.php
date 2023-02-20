<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Output\ConsoleBufferedOutput;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Output\ConsoleBufferedOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;

/** @covers \Steevanb\ParallelProcess\Console\Output\ConsoleBufferedOutput::__construct */
final class ConstructTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $output = new ConsoleBufferedOutput();

        static::assertCount(0, $output->getBufferedLines());
        static::assertTrue($output->getBufferedLines()->isReadOnly());
        static::assertSame(32, $output->getVerbosity());
        static::assertInstanceOf(OutputFormatter::class, $output->getFormatter());
    }
}
