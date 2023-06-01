<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Process;

class StartCondition
{
    protected ProcessInterfaceArray $processesTerminated;

    protected ProcessInterfaceArray $processesSuccessful;

    protected ProcessInterfaceArray $processesFailed;

    public function __construct(protected readonly ProcessInterface $process)
    {
        $this->processesTerminated = (new ProcessInterfaceArray())->setReadOnly();
        $this->processesSuccessful = (new ProcessInterfaceArray())->setReadOnly();
        $this->processesFailed = (new ProcessInterfaceArray())->setReadOnly();
    }

    public function addProcessTerminated(Process $process): static
    {
        $this->processesTerminated->setReadOnly(false);
        $this->processesTerminated[] = $process;
        $this->processesTerminated->setReadOnly();

        return $this;
    }

    public function getProcessesTerminated(): ProcessInterfaceArray
    {
        return $this->processesTerminated;
    }

    public function addProcessSuccessful(Process $process): static
    {
        $this->processesSuccessful->setReadOnly(false);
        $this->processesSuccessful[] = $process;
        $this->processesSuccessful->setReadOnly();

        return $this;
    }

    public function getProcessesSuccessful(): ProcessInterfaceArray
    {
        return $this->processesSuccessful;
    }

    public function addProcessFailed(Process $process): static
    {
        $this->processesFailed->setReadOnly(false);
        $this->processesFailed[] = $process;
        $this->processesFailed->setReadOnly();

        return $this;
    }

    public function getProcessesFailed(): ProcessInterfaceArray
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
        if ($this->process instanceof TearDownProcess) {
            $return = $this->tearDownProcessCanBeStarted();
        } else {
            $return = $this->standardProcessCanBeStarted();
        }

        return $return;
    }

    public function isCanceled(): bool
    {
        $return = false;

        foreach ($this->getProcessesSuccessful()->toArray() as $successfulProcess) {
            if (
                ($successfulProcess->isTerminated() && $successfulProcess->isSuccessful() === false)
                || $successfulProcess->isCanceled()
            ) {
                $return = true;
                break;
            }
        }

        if ($return === false) {
            foreach ($this->getProcessesFailed()->toArray() as $failedProcess) {
                if (
                    ($failedProcess->isTerminated() && $failedProcess->isSuccessful())
                    || $failedProcess->isCanceled()
                ) {
                    $return = true;
                    break;
                }
            }
        }

        return $return;
    }

    protected function standardProcessCanBeStarted(): bool
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

    protected function tearDownProcessCanBeStarted(): bool
    {
        $processes = new ProcessInterfaceArray(
            array_merge(
                $this->getProcessesSuccessful()->toArray(),
                $this->getProcessesTerminated()->toArray(),
                $this->getProcessesFailed()->toArray(),
            )
        );

        $return = true;
        foreach ($processes->toArray() as $process) {
            if ($process->isTerminated() === false && $process->isCanceled() === false) {
                $return = false;
                break;
            }
        }

        return $return;
    }
}
