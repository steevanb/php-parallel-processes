<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Output\ConsoleBufferedOutput;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Output\ConsoleBufferedOutput;

/**
 * @covers \Steevanb\ParallelProcess\Console\Output\ConsoleBufferedOutput::writeBufferedLines
 * @covers \Steevanb\ParallelProcess\Console\Output\ConsoleBufferedOutput::getBufferedLines
 */
final class WriteBufferedLinesTest extends TestCase
{
    public function testWriteOneLine(): void
    {
        $output = $this
            ->getMockBuilder(ConsoleBufferedOutput::class)
            ->onlyMethods(['doWrite'])
            ->getMock();

        $output
            ->expects(static::once())
            ->method('doWrite')
            ->with('foo', false);

        $output
            ->getBufferedLines()
            ->setReadOnly(false)
            ->add('foo');
        $output->writeBufferedLines();

        static::assertCount(0, $output->getBufferedLines());
    }
}
