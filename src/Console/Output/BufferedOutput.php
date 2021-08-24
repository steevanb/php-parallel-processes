<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Console\Output;

use steevanb\PhpTypedArray\ScalarArray\StringArray;
use Symfony\Component\Console\{
    Formatter\OutputFormatterInterface,
    Output\ConsoleOutput,
};

/**
 * Use this implementation of OutputInterface to avoid clipping of ConsoleOutput when multiple lines are written
 * Every calls to write() or writeln() are not written but stored.
 * Then you need to manually call writeBufferedLines() to effectively write lines
 */
class BufferedOutput extends ConsoleOutput
{
    protected StringArray $linesToWrite;

    public function __construct(
        int $verbosity = self::VERBOSITY_NORMAL,
        bool $decorated = null,
        OutputFormatterInterface $formatter = null
    ) {
        parent::__construct($verbosity, $decorated, $formatter);

        $this->linesToWrite = new StringArray();
    }

    /** @param iterable|string $messages */
    public function write($messages, bool $newline = false, int $options = self::OUTPUT_NORMAL): void
    {
        if (is_iterable($messages)) {
            foreach ($messages as $message) {
                $this->linesToWrite[] = $message . ($newline ? "\n" : null);
            }
        } else {
            $this->linesToWrite[] = $messages . ($newline ? "\n" : null);
        }
    }

    public function writeBufferedLines(): self
    {
        parent::doWrite(implode('', $this->linesToWrite->toArray()), false);
        $this->linesToWrite = new StringArray();

        return $this;
    }
}
