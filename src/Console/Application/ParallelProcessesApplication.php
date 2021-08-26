<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Console\Application;

use Steevanb\ParallelProcess\{
    Console\Application\Theme\DefaultTheme,
    Console\Application\Theme\ThemeInterface,
    Console\Output\BufferedOutput,
    Process\ParallelProcess,
    Process\ParallelProcessArray
};
use Symfony\Component\Console\{
    Input\InputInterface,
    Output\OutputInterface,
    SingleCommandApplication
};

class ParallelProcessesApplication extends SingleCommandApplication
{
    protected ParallelProcessArray $parallelProcesses;

    protected ThemeInterface $theme;

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->parallelProcesses = new ParallelProcessArray();
        $this
            ->setCode([$this, 'runParallelProcesses'])
            ->setTheme(new DefaultTheme());
    }

    public function addParallelProcess(ParallelProcess $process): self
    {
        $this->parallelProcesses[] = $process;

        return $this;
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
        $this
            ->getTheme()
            ->outputParallelProcessesState($output, $this->getParallelProcesses());

        $this
            ->startProcesses()
            ->waitProcessesTermination($output);

        $this->getTheme()->outputSummary($output, $this->getParallelProcesses());

        return $this->getExitCode();
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
                }
            }

            $this
                ->getTheme()
                ->resetOutput($output, $this->getParallelProcesses())
                ->outputParallelProcessesState($output, $this->getParallelProcesses());

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
}
