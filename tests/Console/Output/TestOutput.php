<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Output;

use Symfony\Component\Console\{
    Formatter\NullOutputFormatter,
    Formatter\OutputFormatterInterface,
    Output\OutputInterface
};

final class TestOutput implements OutputInterface
{
    protected string $outputed = '';

    protected int $verbosity = self::VERBOSITY_NORMAL;

    protected bool $decorated = false;

    protected OutputFormatterInterface $formatter;

    public function __construct()
    {
        $this->formatter = new NullOutputFormatter();
    }

    /** @param string|iterable $messages */
    public function write($messages, bool $newline = false, int $options = 0): void
    {
        if (is_string($messages)) {
            $this->outputed .= $messages;
        /** @phpstan-ignore-next-line Call to function is_iterable() with iterable will always evaluate to true. */
        } elseif (is_iterable($messages)) {
            foreach ($messages as $message) {
                $this->outputed .= $message;
            }
        } else {
            throw new \Exception('Unknown type for $messages.');
        }

        if ($newline) {
            $this->outputed .= "\n";
        }
    }

    /** @param string|iterable $messages */
    public function writeln($messages, int $options = 0): void
    {
        $this->write($messages, true, $options);
    }

    public function setVerbosity(int $level): void
    {
        $this->verbosity = $level;
    }

    public function getVerbosity(): int
    {
        return $this->verbosity;
    }

    public function isQuiet(): bool
    {
        return $this->getVerbosity() === static::VERBOSITY_QUIET;
    }

    public function isVerbose(): bool
    {
        return $this->getVerbosity() === static::VERBOSITY_VERBOSE;
    }

    public function isVeryVerbose(): bool
    {
        return $this->getVerbosity() === static::VERBOSITY_VERY_VERBOSE;
    }

    public function isDebug(): bool
    {
        return $this->getVerbosity() === static::VERBOSITY_DEBUG;
    }

    public function setDecorated(bool $decorated): self
    {
        $this->decorated = $decorated;

        return $this;
    }

    public function isDecorated(): bool
    {
        return $this->decorated;
    }

    public function setFormatter(OutputFormatterInterface $formatter): void
    {
        $this->formatter = $formatter;
    }

    public function getFormatter(): OutputFormatterInterface
    {
        return $this->formatter;
    }

    public function getOutputed(): string
    {
        return $this->outputed;
    }
}
