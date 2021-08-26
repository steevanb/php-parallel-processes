<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Process;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class ParallelProcess
{
    protected string $name;

    protected Process $process;

    protected int $standardOutputVerbosity = OutputInterface::VERBOSITY_VERBOSE;

    protected int $errorOutputVerbosity = OutputInterface::VERBOSITY_VERBOSE;

    protected int $failureStandardOutputVerbosity = OutputInterface::VERBOSITY_NORMAL;

    protected int $failureErrorOutputVerbosity = OutputInterface::VERBOSITY_NORMAL;

    public function __construct(Process $process, string $name = null)
    {
        if (is_string($name) === false) {
            // Process command line start and end with ', we remove the last one with trim()
            $name = basename(trim($process->getCommandLine(), "'"));
        }
        $this->name = $name;
        $this->process = $process;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getProcess(): Process
    {
        return $this->process;
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
}
