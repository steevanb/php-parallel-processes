<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Console\Application\Theme;

use Steevanb\ParallelProcess\{
    Console\Output\BufferedOutput,
    Process\ParallelProcess,
    Process\ParallelProcessArray
};
use Symfony\Component\Console\{
    Color,
    Output\OutputInterface
};
use steevanb\PhpTypedArray\ScalarArray\StringArray;
use Symfony\Component\Process\Process;

class DefaultTheme implements ThemeInterface
{
    protected Color $stateRunningColor;

    protected Color $stateSuccessfulColor;

    protected Color $stateErrorColor;

    public function __construct()
    {
        $this
            ->setStateRunningColor(new Color('white', 'magenta'))
            ->setStateSuccessfulColor(new Color('white', 'green'))
            ->setStateErrorColor(new Color('white', 'red'));
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

    public function resetOutput(OutputInterface $output, ParallelProcessArray $parallelProcesses): self
    {
        $output->write("\e[" . count($parallelProcesses) . "A\e[K");

        return $this;
    }

    public function outputParallelProcessesState(OutputInterface $output, ParallelProcessArray $parallelProcesses): self
    {
        /** @var ParallelProcess $parallelProcess */
        foreach ($parallelProcesses->toArray() as $parallelProcess) {
            $this->outputParallelProcessState($output, $parallelProcess);
        }

        $this->writeBufferedLines($output);

        return $this;
    }

    public function outputSummary(OutputInterface $output, ParallelProcessArray $parallelProcesses): self
    {
        $this->resetOutput($output, $parallelProcesses);

        /** @var ParallelProcess $parallelProcess */
        foreach ($parallelProcesses->toArray() as $parallelProcess) {
            $this
                ->outputParallelProcessState($output, $parallelProcess)
                ->outputParallelProcessSummary($output, $parallelProcess);
        }

        $this->writeBufferedLines($output);

        return $this;
    }

    protected function outputParallelProcessSummary(OutputInterface $output, ParallelProcess $parallelProcess): self
    {
        $lines = new StringArray();

        if ($parallelProcess->getProcess()->isSuccessful()) {
            if ($parallelProcess->getStandardOutputVerbosity() <= $output->getVerbosity()) {
                $this->mergeProcessOutput($parallelProcess->getProcess()->getErrorOutput(), $lines);
            }
            if ($parallelProcess->getErrorOutputVerbosity() <= $output->getVerbosity()) {
                $this->mergeProcessOutput($parallelProcess->getProcess()->getOutput(), $lines);
            }
        } else {
            if ($parallelProcess->getFailureStandardOutputVerbosity() <= $output->getVerbosity()) {
                $this->mergeProcessOutput($parallelProcess->getProcess()->getOutput(), $lines);
            }
            if ($parallelProcess->getFailureErrorOutputVerbosity() <= $output->getVerbosity()) {
                $this->mergeProcessOutput($parallelProcess->getProcess()->getErrorOutput(), $lines);
            }
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

    protected function outputParallelProcessState(OutputInterface $output, ParallelProcess $parallelProcess): self
    {
        $output->writeln(
            $this->getProcessStateColor($parallelProcess->getProcess())->apply(' > ')
            . ' '
            . $parallelProcess->getName()
        );

        return $this;
    }

    protected function getProcessStateColor(Process $process): Color
    {
        if ($process->getStatus() === Process::STATUS_READY || $process->isRunning()) {
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
