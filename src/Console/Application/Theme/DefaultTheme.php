<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Console\Application\Theme;

use Steevanb\ParallelProcess\{
    Console\Output\ConsoleBufferedOutput,
    Exception\ParallelProcessException,
    Process\Process,
    Process\ProcessInterface,
    Process\ProcessInterfaceCollection
};
use Symfony\Component\Console\{
    Color,
    Output\OutputInterface
};
use Steevanb\PhpCollection\ScalarCollection\StringCollection;

class DefaultTheme implements ThemeInterface
{
    protected Color $stateReadyColor;

    protected string $stateReadyIcon = '>';

    protected Color $stateCanceledColor;

    protected string $stateCanceledIcon = '*';

    protected Color $stateRunningColor;

    protected string $stateRunningIcon = '▶';

    protected Color $stateSuccessfulColor;

    protected string $stateSuccessfulIcon = '✓';

    protected Color $stateErrorColor;

    protected string $stateErrorIcon = '✘';

    protected int $executionTimeVerbosity = OutputInterface::VERBOSITY_VERBOSE;

    protected int $statusLabelVerbosity = OutputInterface::VERBOSITY_VERBOSE;

    public function __construct()
    {
        $this
            ->setStateReadyColor(new Color('white', 'magenta'))
            ->setStateCanceledColor(new Color('black', 'yellow'))
            ->setStateRunningColor(new Color('white', 'blue'))
            ->setStateSuccessfulColor(new Color('white', 'green'))
            ->setStateErrorColor(new Color('white', 'red'));
    }

    public function setStateReadyColor(Color $color): static
    {
        $this->stateReadyColor = $color;

        return $this;
    }

    public function getStateReadyColor(): Color
    {
        return $this->stateReadyColor;
    }

    public function setStateReadyIcon(string $icon): static
    {
        $this->stateReadyIcon = $icon;

        return $this;
    }

    public function getStateReadyIcon(): string
    {
        return $this->stateReadyIcon;
    }

    public function setStateCanceledColor(Color $color): static
    {
        $this->stateCanceledColor = $color;

        return $this;
    }

    public function getStateCanceledColor(): Color
    {
        return $this->stateCanceledColor;
    }

    public function setStateCanceledIcon(string $icon): static
    {
        $this->stateCanceledIcon = $icon;

        return $this;
    }

    public function getStateCanceledIcon(): string
    {
        return $this->stateCanceledIcon;
    }

    public function setStateRunningColor(Color $color): static
    {
        $this->stateRunningColor = $color;

        return $this;
    }

    public function getStateRunningColor(): Color
    {
        return $this->stateRunningColor;
    }

    public function setStateRunningIcon(string $icon): static
    {
        $this->stateRunningIcon = $icon;

        return $this;
    }

    public function getStateRunningIcon(): string
    {
        return $this->stateRunningIcon;
    }

    public function setStateSuccessfulColor(Color $color): static
    {
        $this->stateSuccessfulColor = $color;

        return $this;
    }

    public function getStateSuccessfulColor(): Color
    {
        return $this->stateSuccessfulColor;
    }

    public function setStateSuccessfulIcon(string $icon): static
    {
        $this->stateSuccessfulIcon = $icon;

        return $this;
    }

    public function getStateSuccessfulIcon(): string
    {
        return $this->stateSuccessfulIcon;
    }

    public function setStateErrorColor(Color $color): static
    {
        $this->stateErrorColor = $color;

        return $this;
    }

    public function getStateErrorColor(): Color
    {
        return $this->stateErrorColor;
    }

    public function setStateErrorIcon(string $icon): static
    {
        $this->stateErrorIcon = $icon;

        return $this;
    }

    public function getStateErrorIcon(): string
    {
        return $this->stateErrorIcon;
    }

    public function setExecutionTimeVerbosity(int $verbosity): static
    {
        $this->executionTimeVerbosity = $verbosity;

        return $this;
    }

    public function getExecutionTimeVerbosity(): int
    {
        return $this->executionTimeVerbosity;
    }

    public function setStatusLabelVerbosity(int $verbosity): static
    {
        $this->statusLabelVerbosity = $verbosity;

        return $this;
    }

    public function getStatusLabelVerbosity(): int
    {
        return $this->statusLabelVerbosity;
    }

    public function resetOutput(OutputInterface $output, ProcessInterfaceCollection $processes): static
    {
        for ($reset = 0; $reset < $processes->count(); $reset++) {
            $output->write("\e[1A\e[K");
        }

        return $this;
    }

    #[\Override]
    public function outputStart(OutputInterface $output, ProcessInterfaceCollection $processes): static
    {
        foreach ($processes->toArray() as $process) {
            $this->outputProcessState($output, $process);
        }

        $this->writeBufferedLines($output);

        return $this;
    }

    #[\Override]
    public function outputProcessesState(OutputInterface $output, ProcessInterfaceCollection $processes): static
    {
        $this->resetOutput($output, $processes);

        foreach ($processes->toArray() as $process) {
            $this->outputProcessState($output, $process);
        }

        $this->writeBufferedLines($output);

        return $this;
    }

    #[\Override]
    public function outputSummary(OutputInterface $output, ProcessInterfaceCollection $processes): static
    {
        $this->resetOutput($output, $processes);

        foreach ($processes->toArray() as $process) {
            $this
                ->outputProcessState($output, $process, true)
                ->outputProcessSummary($output, $process);
        }

        $this->writeBufferedLines($output);

        return $this;
    }

    protected function outputProcessSummary(OutputInterface $output, ProcessInterface $process): static
    {
        $lines = new StringCollection();

        if ($process->isTerminated()) {
            if ($process->isSuccessful()) {
                if ($process->getStandardOutputVerbosity() <= $output->getVerbosity()) {
                    $this->mergeProcessOutput(
                        $output,
                        $process->getOutputSummaryPrefix(),
                        $process->getOutput(),
                        $lines
                    );
                }
                if ($process->getErrorOutputVerbosity() <= $output->getVerbosity()) {
                    $this->mergeProcessOutput(
                        $output,
                        $process->getOutputSummaryPrefix(),
                        $process->getErrorOutput(),
                        $lines
                    );
                }
            } else {
                if ($process->getFailureStandardOutputVerbosity() <= $output->getVerbosity()) {
                    $this->mergeProcessOutput(
                        $output,
                        $process->getOutputSummaryPrefix(),
                        $process->getOutput(),
                        $lines
                    );
                }
                if ($process->getFailureErrorOutputVerbosity() <= $output->getVerbosity()) {
                    $this->mergeProcessOutput(
                        $output,
                        $process->getOutputSummaryPrefix(),
                        $process->getErrorOutput(),
                        $lines
                    );
                }
            }
        } elseif ($process->isCanceled()) {
            if ($process->getCanceledOutputVerbosity() <= $output->getVerbosity()) {
                $this->mergeProcessOutput(
                    $output,
                    $process->getOutputSummaryPrefix(),
                    'Process has not been started due to start conditions.',
                    $lines
                );
            }
        } else {
            throw new ParallelProcessException('Unknown process state.');
        }

        $this->removeLastEmptyLines($lines);

        if ($lines->count() > 0) {
            $output->writeln($lines->toArray());
        }

        return $this;
    }

    protected function mergeProcessOutput(
        OutputInterface $output,
        ?string $prefix,
        string $processOutput,
        StringCollection $lines
    ): static {
        $lines->merge(
            new StringCollection(
                array_map(
                    fn(string $line): string => $prefix . ($output->isDecorated() ? '    ' . $line : '  ' . $line),
                    explode("\n", $processOutput)
                )
            )
        );

        return $this;
    }

    protected function removeLastEmptyLines(StringCollection $lines): static
    {
        while (
            $lines->count() >= 1
            && trim($lines->get($lines->count() - 1)) === ''
        ) {
            $lines->remove($lines->count() - 1);
        }

        return $this;
    }

    protected function outputProcessState(
        OutputInterface $output,
        ProcessInterface $process,
        bool $isSummary = false
    ): static {
        if ($output->isDecorated()) {
            $state = $this
                ->getProcessStateColor($process)
                ->apply(' ' . $this->getProcessStateIcon($process) . ' ') . ' ';
        } else {
            $state = $this->getProcessStateIcon($process) . ' ';
        }

        $title = $process->getName();

        if ($output->getVerbosity() >= $this->getStatusLabelVerbosity()) {
            $title .= ' - ' . $this->getStatusLabel($process);
        }

        if ($output->getVerbosity() >= $this->getExecutionTimeVerbosity() && $process->isStarted()) {
            $title .= ' - ' . $this->getExecutionTimeLabel($process->getExecutionTime());
        }

        if ($isSummary && $this->processWillHaveOutput($output, $process) && $output->isDecorated()) {
            $state .= $this->getProcessStateColor($process)->apply(
                ' ' . $title . str_repeat(' ', 79 - strlen($title))
            );
        } else {
            $state .= $title;
        }

        $output->writeln($process->getOutputStatePrefix() . $state);

        return $this;
    }

    protected function processWillHaveOutput(OutputInterface $output, ProcessInterface $process): bool
    {
        return
            (
                $process->isCanceled()
                && $process->getCanceledOutputVerbosity() <= $output->getVerbosity()
            ) || (
                $process->isTerminated()
                && $process->isSuccessful()
                && (
                    $process->getStandardOutputVerbosity() <= $output->getVerbosity()
                    || $process->getErrorOutputVerbosity() <= $output->getVerbosity()
                )
            ) || (
                $process->isTerminated()
                && $process->isSuccessful() === false
                && (
                    $process->getFailureStandardOutputVerbosity() <= $output->getVerbosity()
                    || $process->getFailureErrorOutputVerbosity() <= $output->getVerbosity()
                )
            );
    }

    protected function getProcessStateColor(ProcessInterface $process): Color
    {
        if ($process->isCanceled()) {
            $return = $this->getStateCanceledColor();
        } elseif ($process->getStatus() === Process::STATUS_READY) {
            $return = $this->getStateReadyColor();
        } elseif ($process->isRunning()) {
            $return = $this->getStateRunningColor();
        } elseif ($process->isTerminated() && $process->isSuccessful()) {
            $return = $this->getStateSuccessfulColor();
        } elseif ($process->isTerminated() && $process->isSuccessful() === false) {
            $return = $this->getStateErrorColor();
        } else {
            throw new \Exception('Unknown process state.');
        }

        return $return;
    }

    protected function getProcessStateIcon(ProcessInterface $process): string
    {
        if ($process->isCanceled()) {
            $return = $this->getStateCanceledIcon();
        } elseif ($process->getStatus() === Process::STATUS_READY) {
            $return = $this->getStateReadyIcon();
        } elseif ($process->isRunning()) {
            $return = $this->getStateRunningIcon();
        } elseif ($process->isTerminated() && $process->isSuccessful()) {
            $return = $this->getStateSuccessfulIcon();
        } elseif ($process->isTerminated() && $process->isSuccessful() === false) {
            $return = $this->getStateErrorIcon();
        } else {
            throw new \Exception('Unknown process state.');
        }

        return $return;
    }

    protected function getStatusLabel(ProcessInterface $process): string
    {
        if ($process->isCanceled()) {
            $return = 'Canceled';
        } elseif ($process->getStatus() === Process::STATUS_READY) {
            $return = 'Waiting';
        } elseif ($process->isRunning()) {
            $return = 'Running';
        } elseif ($process->isTerminated() && $process->isSuccessful()) {
            $return = 'Success';
        } elseif ($process->isTerminated() && $process->isSuccessful() === false) {
            $return = 'Error';
        } else {
            throw new \Exception('Unknown process state.');
        }

        return $return;
    }

    protected function getExecutionTimeLabel(int $executionTime): string
    {
        if ($executionTime >= 60000) {
            $return = number_format($executionTime / 60000, 2) . ' min';
        } elseif ($executionTime >= 1000) {
            $return = number_format($executionTime / 1000, 1) . ' sec';
        } else {
            $return = $executionTime . ' ms';
        }

        return $return;
    }

    protected function writeBufferedLines(OutputInterface $output): static
    {
        if ($output instanceof ConsoleBufferedOutput) {
            $output->writeBufferedLines();
        }

        return $this;
    }
}
