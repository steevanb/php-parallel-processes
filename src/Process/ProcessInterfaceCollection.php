<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Process;

use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

/** @extends AbstractObjectCollection<ProcessInterface> */
class ProcessInterfaceCollection extends AbstractObjectCollection
{
    #[\Override]
    public static function getValueFqcn(): string
    {
        return ProcessInterface::class;
    }

    public function getReady(): ProcessInterfaceCollection
    {
        $return = new ProcessInterfaceCollection();

        foreach ($this->toArray() as $process) {
            if ($process->getStatus() === Process::STATUS_READY) {
                $return->add($process);
            }
        }

        return $return;
    }

    public function getStarted(): ProcessInterfaceCollection
    {
        $return = new ProcessInterfaceCollection();

        foreach ($this->toArray() as $process) {
            if ($process->getStatus() === Process::STATUS_STARTED) {
                $return->add($process);
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
