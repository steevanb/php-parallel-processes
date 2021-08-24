<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Console;

use Steevanb\ParallelProcess\{
    Console\Output\BufferedOutput,
    Process\ParallelProcess,
    Process\ParallelProcessArray
};
use Symfony\Component\Console\{
    Input\InputInterface,
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
        $this->setCode([$this, 'runParallelProcesses']);
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

        return parent::run($input, $output);
    }

    protected function runParallelProcesses(InputInterface $input, OutputInterface $output): int
    {
        return $this
            ->outputProcesses($output, false)
            ->startProcesses()
            ->waitProcessesTermination($output)
            ->outputSummary($output)
            ->getExitCode();
    }

    protected function startProcesses(): self
    {
        foreach ($this->getParallelProcesses()->toArray() as $parallelProcess) {
            $parallelProcess->getProcess()->start();
        }

        return $this;
    }

    protected function waitProcessesTermination(OutputInterface $output): self
    {
        $terminated = 0;
        while ($terminated !== count($this->getParallelProcesses())) {
            $terminated = 0;

            /** @var ParallelProcess $parallelProcess */
            foreach ($this->getParallelProcesses()->toArray() as $parallelProcess) {
                if ($parallelProcess->getProcess()->isTerminated()) {
                    $terminated++;
                    $this->outputProcesses($output);
                }
            }

            usleep(100000);
        }

        return $this;
    }

    protected function getExitCode(): int
    {
        $return = static::SUCCESS;
        /** @var ParallelProcess $parallelProcess */
        foreach ($this->getParallelProcesses()->toArray() as $parallelProcess) {
            if ($parallelProcess->getProcess()->isSuccessful() === false) {
                $return = static::FAILURE;
                break;
            }
        }

        return $return;
    }

    protected function createDefaultOutput(): OutputInterface
    {
        return new BufferedOutput();
    }

    protected function outputProcesses(OutputInterface $output, bool $rewrite = true): self
    {
        if ($rewrite) {
            $this->resetOutput($output);
        }

        foreach ($this->getParallelProcesses()->toArray() as $parallelProcess) {
            $output->writeln($this->getParallelProcessStateOutput($parallelProcess));
        }
        $this->writeBufferedLines($output);

        return $this;
    }

    protected function outputSummary(OutputInterface $output): self
    {
        $this->resetOutput($output);

        /** @var ParallelProcess $parallelProcess */
        foreach ($this->getParallelProcesses()->toArray() as $parallelProcess) {
            $output->writeln($this->getParallelProcessStateOutput($parallelProcess));

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

    protected function resetOutput(OutputInterface $output): self
    {
        $output->write("\e[" . count($this->getParallelProcesses()) . "A\e[K");

        return $this;
    }

    protected function getParallelProcessStateOutput(ParallelProcess $parallelProcess): string
    {
        return
            "\e[" .
            $this->getProcessStateColor($parallelProcess->getProcess())
            . "m > \e[0m "
            . $parallelProcess->getName();
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
