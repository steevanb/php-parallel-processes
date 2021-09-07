<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Output\ConsoleBufferedOutput;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Output\ConsoleBufferedOutput;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;

final class ConstructTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $output = new ConsoleBufferedOutput();

        static::assertCount(0, $output->getBufferedLines());
        static::asserttrue($output->getBufferedLines()->isReadOnly());
        static::assertSame(32, $output->getVerbosity());
        static::assertInstanceOf(OutputFormatterInterface::class, $output->getFormatter());
    }
}
