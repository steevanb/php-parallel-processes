<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Process;

use Steevanb\PhpCollection\ObjectCollection\AbstractObjectCollection;

class ProcessInterfaceCollection extends AbstractObjectCollection
{
    public function __construct(iterable $values = [])
    {
        parent::__construct(ProcessInterface::class, $values);
    }

    public function add(ProcessInterface $process): static
    {
        return $this->doAdd($process);
    }

    public function get(int|string $key): ProcessInterface
    {
        return $this->doGet($key);
    }

    /** @return array<int|string, ProcessInterface> */
    public function toArray(): array
    {
        /** @var array<int|string, ProcessInterface> $return */
        $return = parent::toArray();

        return $return;
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
