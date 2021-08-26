<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Process;

use Steevanb\ParallelProcess\Exception\ParallelProcessException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process as SymfonyProcess;

class Process extends SymfonyProcess
{
    protected string $name;

    protected int $standardOutputVerbosity = OutputInterface::VERBOSITY_VERBOSE;

    protected int $errorOutputVerbosity = OutputInterface::VERBOSITY_VERBOSE;

    protected int $failureStandardOutputVerbosity = OutputInterface::VERBOSITY_NORMAL;

    protected int $failureErrorOutputVerbosity = OutputInterface::VERBOSITY_NORMAL;

    protected ?int $executionTime = null;

    /**
     * @param array<string> $command
     * @param array<string>|null $env
     */
    public function __construct(
        array $command,
        string $cwd = null,
        array $env = null,
        $input = null,
        ?float $timeout = 60
    ) {
        parent::__construct($command, $cwd, $env, $input, $timeout);

        $this->name = basename(trim($command[0], "'"));
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setStandardOutputVerbosity(int $standardOutputVerbosity): self
    {
        $this->standardOutputVerbosity = $standardOutputVerbosity;

        return $this;
    }

    public function getStandardOutputVerbosity(): int
    {
        return $this->standardOutputVerbosity;
    }

    public function setErrorOutputVerbosity(int $errorOutputVerbosity): self
    {
        $this->errorOutputVerbosity = $errorOutputVerbosity;

        return $this;
    }

    public function getErrorOutputVerbosity(): int
    {
        return $this->errorOutputVerbosity;
    }

    public function setFailureStandardOutputVerbosity(int $failureStandardOutputVerbosity): self
    {
        $this->failureStandardOutputVerbosity = $failureStandardOutputVerbosity;

        return $this;
    }

    public function getFailureStandardOutputVerbosity(): int
    {
        return $this->failureStandardOutputVerbosity;
    }

    public function setFailureErrorOutputVerbosity(int $failureErrorOutputVerbosity): self
    {
        $this->failureErrorOutputVerbosity = $failureErrorOutputVerbosity;

        return $this;
    }

    public function getFailureErrorOutputVerbosity(): int
    {
        return $this->failureErrorOutputVerbosity;
    }

    public function getExecutionTime(): int
    {
        if (is_int($this->executionTime) === false) {
            throw new ParallelProcessException('Process must be started before calling "(' . __FUNCTION__ . ')".');
        }

        return $this->executionTime;
    }

    /** @param array<string> $env */
    public function start(callable $callback = null, array $env = []): void
    {
        parent::start($callback, $env);

        $this->executionTime = 0;
    }

    public function wait(callable $callback = null): int
    {
        $return = parent::wait($callback);

        $this->executionTime = (int) ((microtime(true) - $this->getStartTime()) * 1000);

        return $return;
    }

    protected function updateStatus(bool $blocking): void
    {
        if ($this->isStarted() && $this->getPrivateStatusPropertyValue() !== static::STATUS_TERMINATED) {
            $this->executionTime = (int) ((microtime(true) - $this->getStartTime()) * 1000);
        }

        parent::updateStatus($blocking);
    }

    protected function getPrivateStatusPropertyValue(): string
    {
        $reflection = new \ReflectionProperty(SymfonyProcess::class, 'status');
        $reflection->setAccessible(true);
        $return = $reflection->getValue($this);
        $reflection->setAccessible(false);

        return $return;
    }
}
