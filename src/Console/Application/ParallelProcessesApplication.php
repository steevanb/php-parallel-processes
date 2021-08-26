<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Console\Application;

use Steevanb\ParallelProcess\{
    Console\Application\Theme\DefaultTheme,
    Console\Application\Theme\ThemeInterface,
    Console\Output\BufferedOutput,
    Process\Process,
    Process\ProcessArray
};
use Symfony\Component\Console\{
    Input\InputInterface,
    Output\OutputInterface,
    SingleCommandApplication
};

class ParallelProcessesApplication extends SingleCommandApplication
{
    protected ProcessArray $processes;

    protected ThemeInterface $theme;

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->processes = new ProcessArray();
        $this
            ->setCode([$this, 'runProcessesInParallel'])
            ->setTheme(new DefaultTheme());
    }

    public function addProcess(Process $process): self
    {
        $this->processes[] = $process;

        return $this;
    }

    public function getProcesses(): ProcessArray
    {
        return $this->processes;
    }

    public function setTheme(ThemeInterface $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getTheme(): ThemeInterface
    {
        return $this->theme;
    }

    public function run(InputInterface $input = null, OutputInterface $output = null): int
    {
        if ($output instanceof OutputInterface === false) {
            $output = $this->createDefaultOutput();
        }

        return parent::run($input, $output);
    }

    protected function runProcessesInParallel(InputInterface $input, OutputInterface $output): int
    {
        $this
            ->getTheme()
            ->outputProcessesState($output, $this->getProcesses());

        $this
            ->startProcesses()
            ->waitProcessesTermination($output);

        $this->getTheme()->outputSummary($output, $this->getProcesses());

        return $this->getExitCode();
    }

    protected function startProcesses(): self
    {
        foreach ($this->getProcesses()->toArray() as $process) {
            $process->start();
        }

        return $this;
    }

    protected function waitProcessesTermination(OutputInterface $output): self
    {
        $terminated = 0;
        while ($terminated !== count($this->getProcesses())) {
            $terminated = 0;

            /** @var Process $process */
            foreach ($this->getProcesses()->toArray() as $process) {
                if ($process->isTerminated()) {
                    $terminated++;
                }
            }

            $this
                ->getTheme()
                ->resetOutput($output, $this->getProcesses())
                ->outputProcessesState($output, $this->getProcesses());

            usleep(10000);
        }

        return $this;
    }

    protected function getExitCode(): int
    {
        $return = static::SUCCESS;
        /** @var Process $process */
        foreach ($this->getProcesses()->toArray() as $process) {
            if ($process->isSuccessful() === false) {
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
}
