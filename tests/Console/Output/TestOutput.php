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

    /** @var 16|32|64|128|256 */
    protected int $verbosity = self::VERBOSITY_NORMAL;

    protected bool $decorated = false;

    protected OutputFormatterInterface $formatter;

    public function __construct()
    {
        $this->formatter = new NullOutputFormatter();
    }

    /** @param string|iterable $messages */
    #[\Override]
    public function write($messages, bool $newline = false, int $options = 0): void
    {
        if (is_string($messages)) {
            $this->outputed .= $messages;
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
    #[\Override]
    public function writeln($messages, int $options = 0): void
    {
        $this->write($messages, true, $options);
    }

    #[\Override]
    public function setVerbosity(int $level): void
    {
        $this->verbosity = $level;
    }

    #[\Override]
    public function getVerbosity(): int
    {
        return $this->verbosity;
    }

    #[\Override]
    public function isQuiet(): bool
    {
        return $this->getVerbosity() === static::VERBOSITY_QUIET;
    }

    #[\Override]
    public function isVerbose(): bool
    {
        return $this->getVerbosity() === static::VERBOSITY_VERBOSE;
    }

    #[\Override]
    public function isVeryVerbose(): bool
    {
        return $this->getVerbosity() === static::VERBOSITY_VERY_VERBOSE;
    }

    #[\Override]
    public function isDebug(): bool
    {
        return $this->getVerbosity() === static::VERBOSITY_DEBUG;
    }

    #[\Override]
    public function setDecorated(bool $decorated): void
    {
        $this->decorated = $decorated;
    }

    #[\Override]
    public function isDecorated(): bool
    {
        return $this->decorated;
    }

    #[\Override]
    public function setFormatter(OutputFormatterInterface $formatter): void
    {
        $this->formatter = $formatter;
    }

    #[\Override]
    public function getFormatter(): OutputFormatterInterface
    {
        return $this->formatter;
    }

    public function getOutputed(): string
    {
        return $this->outputed;
    }
}
