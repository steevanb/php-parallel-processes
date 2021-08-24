<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Process;

use Symfony\Component\Process\Process;

class ParallelProcess
{
    protected string $name;

    protected Process $process;

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
}
