<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Console\Application\Theme;

use Steevanb\ParallelProcess\Process\ProcessArray;
use Symfony\Component\Console\Output\OutputInterface;

interface ThemeInterface
{
    public function resetOutput(OutputInterface $output, ProcessArray $processes): self;

    public function outputProcessesState(
        OutputInterface $output,
        ProcessArray $processes
    ): self;

    public function outputSummary(OutputInterface $output, ProcessArray $processes): self;
}
