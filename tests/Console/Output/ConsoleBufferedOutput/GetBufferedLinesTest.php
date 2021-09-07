<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Output\ConsoleBufferedOutput;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Output\ConsoleBufferedOutput;

final class GetBufferedLinesTest extends TestCase
{
    public function testWrite(): void
    {
        $output = new ConsoleBufferedOutput();
        static::assertCount(0, $output->getBufferedLines());

        $output->write('foo');
        static::assertTrue($output->getBufferedLines()->isReadOnly());
        static::assertCount(1, $output->getBufferedLines());
        static::assertSame('foo', $output->getBufferedLines()[0]);
    }
}
