<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Console\Application\Theme;

use Steevanb\ParallelProcess\{
    Console\Output\ConsoleBufferedOutput,
    Exception\ParallelProcessException,
    Process\Process,
    Process\ProcessArray
};
use Symfony\Component\Console\{
    Color,
    Output\OutputInterface
};
use steevanb\PhpTypedArray\ScalarArray\StringArray;

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

    public function __construct()
    {
        $this
            ->setStateReadyColor(new Color('white', 'magenta'))
            ->setStateCanceledColor(new Color('black', 'yellow'))
            ->setStateRunningColor(new Color('white', 'blue'))
            ->setStateSuccessfulColor(new Color('white', 'green'))
            ->setStateErrorColor(new Color('white', 'red'));
    }

    public function setStateReadyColor(Color $stateReadyColor): self
    {
        $this->stateReadyColor = $stateReadyColor;

        return $this;
    }

    public function getStateReadyColor(): Color
    {
        return $this->stateReadyColor;
    }

    public function setStateReadyIcon(string $stateReadyIcon): self
    {
        $this->stateReadyIcon = $stateReadyIcon;

        return $this;
    }

    public function getStateReadyIcon(): string
    {
        return $this->stateReadyIcon;
    }

    public function setStateCanceledColor(Color $stateCanceledColor): self
    {
        $this->stateCanceledColor = $stateCanceledColor;

        return $this;
    }

    public function getStateCanceledColor(): Color
    {
        return $this->stateCanceledColor;
    }

    public function setStateCanceledIcon(string $stateCanceledIcon): self
    {
        $this->stateCanceledIcon = $stateCanceledIcon;

        return $this;
    }

    public function getStateCanceledIcon(): string
    {
        return $this->stateCanceledIcon;
    }

    public function setStateRunningColor(Color $stateRunningColor): self
    {
        $this->stateRunningColor = $stateRunningColor;

        return $this;
    }

    public function getStateRunningColor(): Color
    {
        return $this->stateRunningColor;
    }

    public function setStateRunningIcon(string $stateRunningIcon): self
    {
        $this->stateRunningIcon = $stateRunningIcon;

        return $this;
    }

    public function getStateRunningIcon(): string
    {
        return $this->stateRunningIcon;
    }

    public function setStateSuccessfulColor(Color $stateSuccessfulColor): self
    {
        $this->stateSuccessfulColor = $stateSuccessfulColor;

        return $this;
    }

    public function getStateSuccessfulColor(): Color
    {
        return $this->stateSuccessfulColor;
    }

    public function setStateSuccessfulIcon(string $stateSuccessfulIcon): self
    {
        $this->stateSuccessfulIcon = $stateSuccessfulIcon;

        return $this;
    }

    public function getStateSuccessfulIcon(): string
    {
        return $this->stateSuccessfulIcon;
    }

    public function setStateErrorColor(Color $stateErrorColor): self
    {
        $this->stateErrorColor = $stateErrorColor;

        return $this;
    }

    public function getStateErrorColor(): Color
    {
        return $this->stateErrorColor;
    }

    public function setStateErrorIcon(string $stateErrorIcon): self
    {
        $this->stateErrorIcon = $stateErrorIcon;

        return $this;
    }

    public function getStateErrorIcon(): string
    {
        return $this->stateErrorIcon;
    }

    public function setExecutionTimeVerbosity(int $executionTimeVerbosity): self
    {
        $this->executionTimeVerbosity = $executionTimeVerbosity;

        return $this;
    }

    public function getExecutionTimeVerbosity(): int
    {
        return $this->executionTimeVerbosity;
    }

    public function resetOutput(OutputInterface $output, ProcessArray $processes): self
    {
        for ($reset = 0; $reset < $processes->count(); $reset++) {
            $output->write("\e[1A\e[K");
        }

        return $this;
    }

    public function outputStart(OutputInterface $output, ProcessArray $processes): ThemeInterface
    {
        foreach ($processes->toArray() as $process) {
            $this->outputProcessState($output, $process);
        }

        $this->writeBufferedLines($output);

        return $this;
    }

    public function outputProcessesState(OutputInterface $output, ProcessArray $processes): self
    {
        $this->resetOutput($output, $processes);

        foreach ($processes->toArray() as $process) {
            $this->outputProcessState($output, $process);
        }

        $this->writeBufferedLines($output);

        return $this;
    }

    public function outputSummary(OutputInterface $output, ProcessArray $processes): self
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

    protected function outputProcessSummary(OutputInterface $output, Process $process): self
    {
        $lines = new StringArray();

        if ($process->isTerminated()) {
            if ($process->isSuccessful()) {
                if ($process->getStandardOutputVerbosity() <= $output->getVerbosity()) {
                    $this->mergeProcessOutput($output, $process->getErrorOutput(), $lines);
                }
                if ($process->getErrorOutputVerbosity() <= $output->getVerbosity()) {
                    $this->mergeProcessOutput($output, $process->getOutput(), $lines);
                }
            } else {
                if ($process->getFailureStandardOutputVerbosity() <= $output->getVerbosity()) {
                    $this->mergeProcessOutput($output, $process->getOutput(), $lines);
                }
                if ($process->getFailureErrorOutputVerbosity() <= $output->getVerbosity()) {
                    $this->mergeProcessOutput($output, $process->getErrorOutput(), $lines);
                }
            }
        } elseif ($process->isCanceled()) {
            if ($process->getCanceledOutputVerbosity() <= $output->getVerbosity()) {
                $this->mergeProcessOutput($output, 'Process has not beend started due to start conditions.', $lines);
            }
        } else {
            throw new ParallelProcessException('Unknown process state.');
        }

        $this->removeLastEmptyLines($lines);

        if ($lines->count() > 0) {
            $output->writeln($lines);
        }

        return $this;
    }

    protected function mergeProcessOutput(OutputInterface $output, string $processOutput, StringArray $lines): self
    {
        $lines->merge(
            new StringArray(
                array_map(
                    fn(string $line): string => $output->isDecorated() ? '    ' . $line : '  ' . $line,
                    explode("\n", $processOutput)
                )
            )
        );

        return $this;
    }

    protected function removeLastEmptyLines(StringArray $lines): self
    {
        while (
            $lines->count() >= 1
            && is_string($lines[$lines->count() - 1])
            && trim($lines[$lines->count() - 1]) === ''
        ) {
            unset($lines[$lines->count() - 1]);
        }

        return $this;
    }

    protected function outputProcessState(OutputInterface $output, Process $process, bool $isSummary = false): self
    {
        if ($output->isDecorated()) {
            $state = $this
                ->getProcessStateColor($process)
                ->apply(' ' . $this->getProcessStateIcon($process) . ' ') . ' ';
        } else {
            $state = $this->getProcessStateIcon($process) . ' ';
        }

        $title = $process->getName();
        if ($output->getVerbosity() >= $this->getExecutionTimeVerbosity() && $process->isStarted()) {
            $title .= ' (' . $process->getExecutionTime() . 'ms)';
        }

        if ($isSummary && $this->processWillHaveOutput($output, $process) && $output->isDecorated()) {
            $state .= $this->getProcessStateColor($process)->apply(
                ' ' . $title . str_repeat(' ', 79 - strlen($title))
            );
        } else {
            $state .= $title;
        }

        $output->writeln($state);

        return $this;
    }

    protected function processWillHaveOutput(OutputInterface $output, Process $process): bool
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

    protected function getProcessStateColor(Process $process): Color
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

    protected function getProcessStateIcon(Process $process): string
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

    protected function writeBufferedLines(OutputInterface $output): self
    {
        if ($output instanceof ConsoleBufferedOutput) {
            $output->writeBufferedLines();
        }

        return $this;
    }
}
