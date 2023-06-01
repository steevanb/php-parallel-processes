<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Console\Output;

use Symfony\Component\Console\{
    Formatter\OutputFormatterInterface,
    Output\ConsoleOutput,
    Output\OutputInterface
};
use Steevanb\PhpCollection\ScalarCollection\StringCollection;

/**
 * Use this implementation of OutputInterface to avoid clipping of ConsoleOutput when multiple lines are written.
 * Every call to write() or writeln() are not written but stored.
 * Then you need to manually call writeBufferedLines() to effectively write lines.
 */
class ConsoleBufferedOutput extends ConsoleOutput
{
    protected StringCollection $bufferedLines;

    public function __construct(
        int $verbosity = self::VERBOSITY_NORMAL,
        bool $decorated = null,
        OutputFormatterInterface $formatter = null
    ) {
        parent::__construct($verbosity, $decorated, $formatter);

        $this->bufferedLines = (new StringCollection())->setReadOnly();
    }

    /** @param iterable|string $messages */
    public function write($messages, bool $newline = false, int $options = self::OUTPUT_NORMAL): void
    {
        // Mostly copied from Symfony\Component\Console\Output\Output::write()

        if (is_iterable($messages) === false) {
            $messages = [$messages];
        }

        $types = static::OUTPUT_NORMAL | static::OUTPUT_RAW | static::OUTPUT_PLAIN;
        // @phpstan-ignore-next-line
        $type = $types & $options ?: static::OUTPUT_NORMAL;

        $verbosities = static::VERBOSITY_QUIET
            | static::VERBOSITY_NORMAL
            | static::VERBOSITY_VERBOSE
            | static::VERBOSITY_VERY_VERBOSE
            | static::VERBOSITY_DEBUG;
        // @phpstan-ignore-next-line
        $verbosity = $verbosities & $options ?: static::VERBOSITY_NORMAL;

        if ($verbosity > $this->getVerbosity()) {
            return;
        }

        foreach ($messages as $message) {
            if ($type === OutputInterface::OUTPUT_NORMAL) {
                $message = $this->getFormatter()->format($message);
            } elseif ($type === OutputInterface::OUTPUT_PLAIN) {
                $message = strip_tags($this->getFormatter()->format($message) ?? '');
            }

            // Difference with original write() is here
            $this
                ->bufferedLines
                ->setReadOnly(false)
                ->add($message . ($newline ? "\n" : null))
                ->setReadOnly();
        }
    }

    public function writeBufferedLines(): static
    {
        $this->doWrite(implode('', $this->bufferedLines->toArray()), false);

        $this->bufferedLines = new StringCollection();

        return $this;
    }

    public function getBufferedLines(): StringCollection
    {
        return $this->bufferedLines;
    }
}
