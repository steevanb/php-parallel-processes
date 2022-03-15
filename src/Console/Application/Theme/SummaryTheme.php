<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Console\Application\Theme;

use Steevanb\ParallelProcess\Process\ProcessArray;
use Symfony\Component\Console\Output\OutputInterface;

class SummaryTheme extends DefaultTheme
{
    public function outputStart(OutputInterface $output, ProcessArray $processes): ThemeInterface
    {
        $output->writeln(
            'Starting <info>'
            . $processes->count()
            . '</info> processe'
            . ($processes->count() !== 1 ? 's' : null)
            . '...'
        );
        $this->writeBufferedLines($output);

        return $this;
    }

    public function outputProcessesState(OutputInterface $output, ProcessArray $processes): self
    {
        return $this;
    }

    public function resetOutput(OutputInterface $output, ProcessArray $processes): self
    {
        return $this;
    }
}
