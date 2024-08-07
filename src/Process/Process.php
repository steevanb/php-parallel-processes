<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Process;

use Steevanb\ParallelProcess\Exception\ParallelProcessException;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process as SymfonyProcess;

class Process extends SymfonyProcess implements ProcessInterface
{
    protected string $name;

    protected ?string $outputStatePrefix = null;

    protected ?string $outputSummaryPrefix = null;

    protected int $standardOutputVerbosity = OutputInterface::VERBOSITY_VERY_VERBOSE;

    protected int $errorOutputVerbosity = OutputInterface::VERBOSITY_VERY_VERBOSE;

    protected int $canceledOutputVerbosity = OutputInterface::VERBOSITY_VERY_VERBOSE;

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
        $this->startCondition = new StartCondition($this);
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

    public function setOutputStatePrefix(string $prefix = null): static
    {
        $this->outputStatePrefix = $prefix;

        return $this;
    }

    public function getOutputStatePrefix(): ?string
    {
        return $this->outputStatePrefix;
    }

    public function setOutputSummaryPrefix(string $prefix = null): static
    {
        $this->outputSummaryPrefix = $prefix;

        return $this;
    }

    public function getOutputSummaryPrefix(): ?string
    {
        return $this->outputSummaryPrefix;
    }

    public function setOutputPrefix(string $prefix = null): static
    {
        return $this
            ->setOutputStatePrefix($prefix)
            ->setOutputSummaryPrefix($prefix);
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
        $this->executionTime = 0;

        parent::start($callback, $env);
    }

    protected function updateStatus(bool $blocking): void
    {
        // Do not call getStatuts() here to get the status, or it will call updateStatus() and do an infinite loop
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
