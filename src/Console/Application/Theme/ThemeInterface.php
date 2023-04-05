<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Console\Application\Theme;

use Steevanb\ParallelProcess\Process\ProcessInterfaceArray;
use Symfony\Component\Console\Output\OutputInterface;

interface ThemeInterface
{
    public function outputStart(OutputInterface $output, ProcessInterfaceArray $processes): static;

    public function outputProcessesState(OutputInterface $output, ProcessInterfaceArray $processes): static;

    public function outputSummary(OutputInterface $output, ProcessInterfaceArray $processes): static;
}
