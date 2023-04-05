<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Process;

use Steevanb\PhpTypedArray\ObjectArray\ObjectArray;

class ProcessInterfaceArray extends ObjectArray
{
    public function __construct(iterable $values = [])
    {
        parent::__construct($values, ProcessInterface::class);
    }

    public function current(): ?Process
    {
        return parent::current();
    }

    /** @return array<Process> */
    public function toArray(): array
    {
        return parent::toArray();
    }

    public function getReady(): ProcessInterfaceArray
    {
        $return = new ProcessInterfaceArray();

        foreach ($this->toArray() as $process) {
            if ($process->getStatus() === Process::STATUS_READY) {
                $return[] = $process;
            }
        }

        return $return;
    }

    public function getStarted(): ProcessInterfaceArray
    {
        $return = new ProcessInterfaceArray();

        foreach ($this->toArray() as $process) {
            if ($process->getStatus() === Process::STATUS_STARTED) {
                $return[] = $process;
            }
        }

        return $return;
    }

    public function countRunning(): int
    {
        $return = 0;
        foreach ($this->toArray() as $process) {
            if ($process->isRunning()) {
                $return++;
            }
        }

        return $return;
    }
}
