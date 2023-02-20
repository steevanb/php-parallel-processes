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

    protected int $canceledOutputVerbosity = OutputInterface::VERBOSITY_VERBOSE;

    protected int $failureStandardOutputVerbosity = OutputInterface::VERBOSITY_NORMAL;

    protected int $failureErrorOutputVerbosity = OutputInterface::VERBOSITY_NORMAL;

    protected ?int $executionTime = null;

    protected StartCondition $startCondition;

    protected bool $canceled = false;

    protected bool $canceledAsError = true;

    protected bool $spreadErrorToApplicationExitCode = true;

    /**
     * @param array<string> $command
     * @param array<string> $env
     */
    public function __construct(
        array $command,
        string $cwd = null,
        array $env = [],
        $input = null,
        ?float $timeout = 60
    ) {
        parent::__construct($command, $cwd, $env, $input, $timeout);

        $this->setName(basename($command[0]));
        $this->startCondition = new StartCondition();
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setStandardOutputVerbosity(int $standardOutputVerbosity): static
    {
        $this->standardOutputVerbosity = $standardOutputVerbosity;

        return $this;
    }

    public function getStandardOutputVerbosity(): int
    {
        return $this->standardOutputVerbosity;
    }

    public function setErrorOutputVerbosity(int $errorOutputVerbosity): static
    {
        $this->errorOutputVerbosity = $errorOutputVerbosity;

        return $this;
    }

    public function getErrorOutputVerbosity(): int
    {
        return $this->errorOutputVerbosity;
    }

    public function setFailureStandardOutputVerbosity(int $failureStandardOutputVerbosity): static
    {
        $this->failureStandardOutputVerbosity = $failureStandardOutputVerbosity;

        return $this;
    }

    public function getFailureStandardOutputVerbosity(): int
    {
        return $this->failureStandardOutputVerbosity;
    }

    public function setFailureErrorOutputVerbosity(int $failureErrorOutputVerbosity): static
    {
        $this->failureErrorOutputVerbosity = $failureErrorOutputVerbosity;

        return $this;
    }

    public function getFailureErrorOutputVerbosity(): int
    {
        return $this->failureErrorOutputVerbosity;
    }

    public function setCanceledOutputVerbosity(int $canceledOutputVerbosity): static
    {
        $this->canceledOutputVerbosity = $canceledOutputVerbosity;

        return $this;
    }

    public function getCanceledOutputVerbosity(): int
    {
        return $this->canceledOutputVerbosity;
    }

    public function getExecutionTime(): int
    {
        if (is_int($this->executionTime) === false) {
            throw new ParallelProcessException('Process must be started before calling "' . __FUNCTION__ . '()".');
        }

        return $this->executionTime;
    }

    public function getStartCondition(): StartCondition
    {
        return $this->startCondition;
    }

    public function setCanceled(bool $canceled = true): static
    {
        $this->canceled = $canceled;

        return $this;
    }

    public function isCanceled(): bool
    {
        return $this->canceled;
    }

    public function setCanceledAsError(bool $canceledAsError = true): static
    {
        $this->canceledAsError = $canceledAsError;

        return $this;
    }

    public function isCanceledAsError(): bool
    {
        return $this->canceledAsError;
    }

    public function setSpreadErrorToApplicationExitCode(bool $spreadErrorToApplicationExitCode = true): static
    {
        $this->spreadErrorToApplicationExitCode = $spreadErrorToApplicationExitCode;

        return $this;
    }

    public function isSpreadErrorToApplicationExitCode(): bool
    {
        return $this->spreadErrorToApplicationExitCode;
    }

    /** @param array<mixed> $env */
    public function start(callable $callback = null, array $env = []): void
    {
        parent::start($callback, $env);

        $this->executionTime = 0;
    }

    public function getStartTime(): float
    {
        // Before symfony/process 5.1, getStartTime() does not exist but $this->starttime already exists and is private
        try {
            $return = parent::getStartTime();
        } catch (\Throwable $exception) {
            $return = $this->getParentPrivatePropertyValue('starttime');
        }

        return $return;
    }

    protected function updateStatus(bool $blocking): void
    {
        if ($this->isStarted() && $this->getParentPrivatePropertyValue('status') !== static::STATUS_TERMINATED) {
            $this->executionTime = (int) ((microtime(true) - $this->getStartTime()) * 1000);
        }

        parent::updateStatus($blocking);
    }

    /** @return mixed */
    protected function getParentPrivatePropertyValue(string $property)
    {
        $reflection = new \ReflectionProperty(SymfonyProcess::class, $property);
        $reflection->setAccessible(true);

        return $reflection->getValue($this);
    }
}
