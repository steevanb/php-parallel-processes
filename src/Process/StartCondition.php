<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Process;

class StartCondition
{
    protected ProcessInterfaceCollection $processesTerminated;

    protected ProcessInterfaceCollection $processesSuccessful;

    protected ProcessInterfaceCollection $processesFailed;

    public function __construct(protected readonly ProcessInterface $process)
    {
        $this->processesTerminated = (new ProcessInterfaceCollection())->setReadOnly();
        $this->processesSuccessful = (new ProcessInterfaceCollection())->setReadOnly();
        $this->processesFailed = (new ProcessInterfaceCollection())->setReadOnly();
    }

    public function addProcessTerminated(ProcessInterface $process): static
    {
        $this
            ->processesTerminated
            ->setReadOnly(false)
            ->add($process)
            ->setReadOnly();

        return $this;
    }

    public function getProcessesTerminated(): ProcessInterfaceCollection
    {
        return $this->processesTerminated;
    }

    public function addProcessSuccessful(ProcessInterface $process): static
    {
        $this
            ->processesSuccessful
            ->setReadOnly(false)
            ->add($process)
            ->setReadOnly();

        return $this;
    }

    public function getProcessesSuccessful(): ProcessInterfaceCollection
    {
        return $this->processesSuccessful;
    }

    public function addProcessFailed(ProcessInterface $process): static
    {
        $this
            ->processesFailed
            ->setReadOnly(false)
            ->add($process)
            ->setReadOnly();

        return $this;
    }

    public function getProcessesFailed(): ProcessInterfaceCollection
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
        $processes = new ProcessInterfaceCollection(
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
