<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Console\Application\Theme;

use Steevanb\ParallelProcess\{
    Console\Output\BufferedOutput,
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

    protected Color $stateCanceledColor;

    protected Color $stateRunningColor;

    protected Color $stateSuccessfulColor;

    protected Color $stateErrorColor;

    protected int $executionTimeVerbosity = OutputInterface::VERBOSITY_VERBOSE;

    public function __construct()
    {
        $this
            ->setStateReadyColor(new Color('white', 'magenta'))
            ->setStateCanceledColor(new Color('white', 'yellow'))
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

    public function setStateCanceledColor(Color $stateCanceledColor): self
    {
        $this->stateCanceledColor = $stateCanceledColor;

        return $this;
    }

    public function getStateCanceledColor(): Color
    {
        return $this->stateCanceledColor;
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

    public function setStateSuccessfulColor(Color $stateSuccessfulColor): self
    {
        $this->stateSuccessfulColor = $stateSuccessfulColor;

        return $this;
    }

    public function getStateSuccessfulColor(): Color
    {
        return $this->stateSuccessfulColor;
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
        $output->write("\e[" . count($processes) . "A\e[K");

        return $this;
    }

    public function outputProcessesState(OutputInterface $output, ProcessArray $processes): self
    {
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
                    $this->mergeProcessOutput($process->getErrorOutput(), $lines);
                }
                if ($process->getErrorOutputVerbosity() <= $output->getVerbosity()) {
                    $this->mergeProcessOutput($process->getOutput(), $lines);
                }
            } else {
                if ($process->getFailureStandardOutputVerbosity() <= $output->getVerbosity()) {
                    $this->mergeProcessOutput($process->getOutput(), $lines);
                }
                if ($process->getFailureErrorOutputVerbosity() <= $output->getVerbosity()) {
                    $this->mergeProcessOutput($process->getErrorOutput(), $lines);
                }
            }
        } elseif ($process->isCanceled()) {
            if ($process->getCanceledOutputVerbosity() <= $output->getVerbosity()) {
                $this->mergeProcessOutput('Process has not beend started due to start conditions.', $lines);
            }
        } else {
            throw new ParallelProcessException('Unknown process state.');
        }

        $this->removeLastEmptyLines($lines);

        $output->writeln($lines);

        return $this;
    }

    protected function mergeProcessOutput(string $processOutput, StringArray $lines): self
    {
        $lines->merge(
            new StringArray(
                array_map(
                    fn(string $line): string => '    ' . $line,
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
        $state = $this->getProcessStateColor($process)->apply(' > ') . ' ';

        $title = $process->getName();
        if ($output->getVerbosity() >= $this->getExecutionTimeVerbosity() && $process->isStarted()) {
            $title .= ' (' . $process->getExecutionTime() . 'ms)';
        }

        if ($isSummary && $this->processWillHaveOutput($output, $process)) {
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

    protected function writeBufferedLines(OutputInterface $output): self
    {
        if ($output instanceof BufferedOutput) {
            $output->writeBufferedLines();
        }

        return $this;
    }
}
