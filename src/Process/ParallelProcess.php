<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Process;

use Symfony\Component\Process\Process;

class ParallelProcess
{
    protected string $name;

    protected Process $process;

    public function __construct(string $name, Process $process)
    {
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
}
