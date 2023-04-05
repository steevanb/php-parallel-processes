<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Console\Application\Theme;

use Steevanb\ParallelProcess\Process\ProcessInterfaceArray;
use Symfony\Component\Console\Output\OutputInterface;

class SummaryTheme extends DefaultTheme
{
    public function outputStart(OutputInterface $output, ProcessInterfaceArray $processes): static
    {
        $output->writeln(
            'Starting <info>'
            . $processes->count()
            . '</info> process'
            . ($processes->count() !== 1 ? 'es' : null)
            . '...'
        );
        $this->writeBufferedLines($output);

        return $this;
    }

    public function outputProcessesState(OutputInterface $output, ProcessInterfaceArray $processes): static
    {
        return $this;
    }

    public function resetOutput(OutputInterface $output, ProcessInterfaceArray $processes): static
    {
        return $this;
    }
}
