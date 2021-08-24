<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Console\Application\Theme;

use Steevanb\ParallelProcess\{
    Console\Output\BufferedOutput,
    Process\ParallelProcess,
    Process\ParallelProcessArray
};
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class DefaultTheme implements ThemeInterface
{
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
            $this->outputParallelProcessState($output, $parallelProcess);

            if ($parallelProcess->getProcess()->isSuccessful() === false) {
                $outputs = explode(
                    "\n",
                    $parallelProcess->getProcess()->getOutput()
                    . "\n"
                    . $parallelProcess->getProcess()->getErrorOutput()
                );
                foreach ($outputs as $line) {
                    $output->writeln('    ' . $line);
                }
            }
        }

        $this->writeBufferedLines($output);

        return $this;
    }

    protected function outputParallelProcessState(OutputInterface $output, ParallelProcess $parallelProcess): self
    {
        $output->writeln(
            "\e[" .
            $this->getProcessStateColor($parallelProcess->getProcess())
            . "m > \e[0m "
            . $parallelProcess->getName()
        );

        return $this;
    }

    protected function getProcessStateColor(Process $process): int
    {
        if ($process->getStatus() === Process::STATUS_READY || $process->isRunning()) {
            $return = 45;
        } elseif ($process->isTerminated() && $process->isSuccessful()) {
            $return = 42;
        } elseif ($process->isTerminated() && $process->isSuccessful() === false) {
            $return = 41;
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
