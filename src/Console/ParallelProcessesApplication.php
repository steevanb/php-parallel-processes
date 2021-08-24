<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Console;

use Steevanb\ParallelProcess\{
    Process\ParallelProcess,
    Process\ParallelProcessArray
};
use Symfony\Component\Console\{
    Input\InputInterface,
    Output\ConsoleOutput,
    Output\OutputInterface,
    SingleCommandApplication
};
use Symfony\Component\Process\Process;

class ParallelProcessesApplication extends SingleCommandApplication
{
    protected ParallelProcessArray $parallelProcesses;

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->parallelProcesses = new ParallelProcessArray();
    }

    public function addParallelProcess(ParallelProcess $process): self
    {
        $this->parallelProcesses[] = $process;

        return $this;
    }

    public function getParallelProcesses(): ParallelProcessArray
    {
        return $this->parallelProcesses;
    }

    public function run(InputInterface $input = null, OutputInterface $output = null): int
    {
        if ($output instanceof OutputInterface === false) {
            $output = $this->createDefaultOutput();
        }

        $this->setCode(
            function () use ($output): void {
                $waitings = $this->getParallelProcesses();

                $this->outputProcesses($output, false);

                foreach ($this->getParallelProcesses()->toArray() as $parallelProcess) {
                    $parallelProcess->getProcess()->start();
                }

                while (count($waitings) > 0) {
                    foreach ($waitings->toArray() as $waitingIndex => $waiting) {
                        if ($waiting->getProcess()->isTerminated()) {
                            unset($waitings[$waitingIndex]);
                            $this->outputProcesses($output);
                        }
                    }
                    usleep(100000);
                }

                $this->outputSummary($output);
            }
        );

        return parent::run($input, $output);
    }

    protected function createDefaultOutput(): OutputInterface
    {
        return new ConsoleOutput();
    }

    protected function outputProcesses(OutputInterface $output, bool $rewrite = true): self
    {
        if ($rewrite) {
            $this->resetOutput($output);
        }

        foreach ($this->getParallelProcesses()->toArray() as $parallelProcess) {
            $this->outputParallelProcessState($output, $parallelProcess);
        }

        return $this;
    }

    protected function outputSummary(OutputInterface $output): self
    {
        $this->resetOutput($output);

        foreach ($this->getParallelProcesses()->toArray() as $parallelProcess) {
            $this->outputParallelProcessState($output, $parallelProcess);

            if ($parallelProcess->getProcess()->isSuccessful() === false) {
                foreach (explode("\n", $parallelProcess->getProcess()->getOutput()) as $line) {
                    $output->writeln('    ' . $line);
                }
            }
        }

        return $this;
    }

    protected function resetOutput(OutputInterface $output): self
    {
        $output->write("\e[" . count($this->getParallelProcesses()) . "A\e[K");

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
}
