<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Process;

class StartCondition
{
    protected ProcessArray $processesTerminated;

    protected ProcessArray $processesSuccessful;

    protected ProcessArray $processesFailed;

    public function __construct()
    {
        $this->processesTerminated = new ProcessArray();
        $this->processesSuccessful = new ProcessArray();
        $this->processesFailed = new ProcessArray();
    }

    public function addProcessTerminated(Process $process): self
    {
        $this->processesTerminated[] = $process;

        return $this;
    }

    public function getProcessesTerminated(): ProcessArray
    {
        return $this->processesTerminated;
    }

    public function addProcessSuccessful(Process $process): self
    {
        $this->processesSuccessful[] = $process;

        return $this;
    }

    public function getProcessesSuccessful(): ProcessArray
    {
        return $this->processesSuccessful;
    }

    public function addProcessFailed(Process $process): self
    {
        $this->processesFailed[] = $process;

        return $this;
    }

    public function getProcessesFailed(): ProcessArray
    {
        return $this->processesFailed;
    }

    public function hasConditions(): bool
    {
        return
            $this->getProcessesTerminated()->count() > 0
            || $this->getProcessesSuccessful()->count() > 0
            || $this->getProcessesFailed()->count() > 0;
    }

    public function canBeStarted(): bool
    {
        $return = true;

        foreach ($this->getProcessesTerminated()->toArray() as $terminatedProcess) {
            if ($terminatedProcess->isTerminated() === false) {
                $return = false;
                break;
            }
        }

        if ($return) {
            foreach ($this->getProcessesSuccessful()->toArray() as $successfulProcess) {
                if ($successfulProcess->isTerminated() === false || $successfulProcess->isSuccessful() === false) {
                    $return = false;
                    break;
                }
            }
        }

        if ($return) {
            foreach ($this->getProcessesFailed()->toArray() as $failedProcess) {
                if ($failedProcess->isTerminated() === false || $failedProcess->isSuccessful()) {
                    $return = false;
                    break;
                }
            }
        }

        return $return;
    }

    public function isCanceled(): bool
    {
        $return = false;

        foreach ($this->getProcessesSuccessful()->toArray() as $successfulProcess) {
            if ($successfulProcess->isTerminated() && $successfulProcess->isSuccessful() === false) {
                $return = true;
                break;
            }
        }

        if ($return === false) {
            foreach ($this->getProcessesFailed()->toArray() as $failedProcess) {
                if ($failedProcess->isTerminated() && $failedProcess->isSuccessful()) {
                    $return = true;
                    break;
                }
            }
        }

        return $return;
    }
}
