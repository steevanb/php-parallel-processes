<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Output\ConsoleBufferedOutput;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Output\ConsoleBufferedOutput;

final class WriteTest extends TestCase
{
    public function testString(): void
    {
        $output = new ConsoleBufferedOutput();
        static::assertCount(0, $output->getBufferedLines());

        $output->write('<comment>foo</comment>');
        static::assertTrue($output->getBufferedLines()->isReadOnly());
        static::assertCount(1, $output->getBufferedLines());
        static::assertLine('foo', $output->getBufferedLines()[0], $output->getFormatter()->isDecorated());
    }

    public function testIterable(): void
    {
        $output = new ConsoleBufferedOutput();
        static::assertCount(0, $output->getBufferedLines());

        $output->write(['<comment>foo</comment>', '<comment>bar</comment>']);
        static::assertTrue($output->getBufferedLines()->isReadOnly());
        static::assertCount(2, $output->getBufferedLines());
        static::assertLine('foo', $output->getBufferedLines()[0], $output->getFormatter()->isDecorated());
        static::assertLine('bar', $output->getBufferedLines()[1], $output->getFormatter()->isDecorated());
    }

    public function testNewLine(): void
    {
        $output = new ConsoleBufferedOutput();
        $output->write('<comment>foo</comment>', true);

        static::assertCount(1, $output->getBufferedLines());
        static::assertLine('foo', $output->getBufferedLines()[0], $output->getFormatter()->isDecorated(), true);
    }

    public function testVerbosity(): void
    {
        $output = new ConsoleBufferedOutput();
        $output->write('foo', false, ConsoleBufferedOutput::VERBOSITY_VERBOSE);

        static::assertCount(0, $output->getBufferedLines());
    }

    public function testOutputRaw(): void
    {
        $output = new ConsoleBufferedOutput();
        $output->write('<comment>foo</comment>', false, ConsoleBufferedOutput::OUTPUT_RAW);

        static::assertCount(1, $output->getBufferedLines());
        static::assertSame('<comment>foo</comment>', $output->getBufferedLines()[0]);
    }

    public function testOutputPlain(): void
    {
        $output = new ConsoleBufferedOutput();
        $output->write('<comment>foo</comment>', false, ConsoleBufferedOutput::OUTPUT_PLAIN);

        static::assertCount(1, $output->getBufferedLines());
        static::assertLine('foo', $output->getBufferedLines()[0], $output->getFormatter()->isDecorated());
    }

    public function testUnknownOutput(): void
    {
        $output = new ConsoleBufferedOutput();
        $output->write('<comment>foo</comment>', false, 8);

        static::assertLine('foo', $output->getBufferedLines()[0], $output->getFormatter()->isDecorated());
    }

    public static function assertLine(string $expected, string $actual, bool $isDecorated, bool $newLine = false): void
    {
        static::assertSame(
            ($isDecorated ? "\e[33m" . $expected . "\e[39m" : $expected) . ($newLine ? "\n" : null),
            $actual
        );
    }
}
