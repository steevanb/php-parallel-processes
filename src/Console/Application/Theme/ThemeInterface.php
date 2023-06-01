<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Console\Application\Theme;

use Steevanb\ParallelProcess\Process\ProcessInterfaceCollection;
use Symfony\Component\Console\Output\OutputInterface;

interface ThemeInterface
{
    public function outputStart(OutputInterface $output, ProcessInterfaceCollection $processes): static;

    public function outputProcessesState(OutputInterface $output, ProcessInterfaceCollection $processes): static;

    public function outputSummary(OutputInterface $output, ProcessInterfaceCollection $processes): static;
}
