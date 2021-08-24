<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Console\Application\Theme;

use Steevanb\ParallelProcess\Process\ParallelProcessArray;
use Symfony\Component\Console\Output\OutputInterface;

interface ThemeInterface
{
    public function resetOutput(OutputInterface $output, ParallelProcessArray $parallelProcesses): self;

    public function outputParallelProcessesState(
        OutputInterface $output,
        ParallelProcessArray $parallelProcesses
    ): self;

    public function outputSummary(OutputInterface $output, ParallelProcessArray $parallelProcesses): self;
}
