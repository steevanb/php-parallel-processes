<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Console\Application;

use Steevanb\ParallelProcess\{
    Console\Application\Theme\DefaultTheme,
    Console\Application\Theme\SummaryTheme,
    Console\Application\Theme\ThemeInterface,
    Console\Output\ConsoleBufferedOutput,
    Exception\ParallelProcessException,
    Process\Process,
    Process\ProcessArray
};
use Symfony\Component\Console\{
    Command\SignalableCommandInterface,
    Input\InputInterface,
    Input\InputOption,
    Output\OutputInterface,
    SingleCommandApplication
};

class ParallelProcessesApplication extends SingleCommandApplication implements SignalableCommandInterface
{
    protected ProcessArray $processes;

    protected ThemeInterface $theme;

    /** Refresh time in microseconds, 10ms by default */
    protected int $refreshInterval = 10000;

    protected ?int $maximumParallelProcesses = null;

    protected bool $canceled = false;

    public function __construct(string $name = null)
    {
        parent::__construct($name);

        $this->processes = new ProcessArray();
        $this
            ->setCode([$this, 'runProcessesInParallel'])
            ->setTheme(new DefaultTheme());
    }

    public function addProcess(Process $process): static
    {
        $this->processes[] = $process;

        return $this;
    }

    public function addProcesses(ProcessArray $processes): static
    {
        foreach ($processes->toArray() as $process) {
            $this->addProcess($process);
        }

        return $this;
    }

    public function hasProcess(Process $process): bool
    {
        $return = false;
        foreach ($this->processes->toArray() as $loopProcess) {
            if (spl_object_id($loopProcess) === spl_object_id($process)) {
                $return = true;
                break;
            }
        }

        return $return;
    }

    public function getProcesses(): ProcessArray
    {
        return $this->processes;
    }

    public function setTheme(ThemeInterface $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getTheme(): ThemeInterface
    {
        return $this->theme;
    }

    /** Set refresh interval in microseconds */
    public function setRefreshInterval(int $refreshInterval): static
    {
        $this->refreshInterval = $refreshInterval;

        return $this;
    }

    /** Get refresh interval in microseconds */
    public function getRefreshInterval(): int
    {
        return $this->refreshInterval;
    }

    public function setMaximumParallelProcesses(?int $maximumParallelProcesses): static
    {
        $this->maximumParallelProcesses = $maximumParallelProcesses;

        return $this;
    }

    public function getMaximumParallelProcesses(): ?int
    {
        return $this->maximumParallelProcesses;
    }

    public function run(InputInterface $input = null, OutputInterface $output = null): int
    {
        if ($output instanceof OutputInterface === false) {
            $output = $this->createDefaultOutput();
        }

        return parent::run($input, $output);
    }

    /** @return array<int, int> */
    public function getSubscribedSignals(): array
    {
        return [SIGINT];
    }

    /** @internal */
    public function handleSignal(int $signal): void
    {
        if ($signal === SIGINT) {
            $this->canceled = true;

            foreach (
                array_merge(
                    $this->getProcesses()->getReady()->toArray(),
                    $this->getProcesses()->getStarted()->toArray()
                ) as $process
            ) {
                $process->stop();
                $process->setCanceled();
            }
        }
    }

    protected function configure(): void
    {
        parent::configure();

        $this
            ->addOption(
                'theme',
                't',
                InputOption::VALUE_REQUIRED,
                'Name or FQCN of the theme to use (examples: default, summary, ' . DefaultTheme::class . ')'
            )
            ->addOption(
                'refresh-interval',
                'r',
                InputOption::VALUE_REQUIRED,
                'Refresh interval in microseconds (example: 100000 for 100ms)'
            );
    }

    protected function isCanceled(): bool
    {
        return $this->canceled;
    }

    protected function runProcessesInParallel(InputInterface $input, OutputInterface $output): int
    {
        $this
            ->defineThemeFromInput($input)
            ->defineRefreshIntervalFromInput($input);

        $this->getTheme()->outputStart($output, $this->getProcesses());

        $this
            ->startProcesses()
            ->waitProcessesTermination($output);

        $this->getTheme()->outputSummary($output, $this->getProcesses());

        return $this->getExitCode();
    }

    protected function defineThemeFromInput(InputInterface $input): static
    {
        $theme = $input->getOption('theme');
        if (is_string($theme)) {
            switch ($theme) {
                case 'default':
                    $this->setTheme(new DefaultTheme());
                    break;
                case 'summary':
                    $this->setTheme(new SummaryTheme());
                    break;
                default:
                    if (class_exists($theme) === false) {
                        throw new ParallelProcessException('Theme "' . $theme . '" not found.');
                    }

                    $themeObject = new $theme();
                    if ($themeObject instanceof ThemeInterface === false) {
                        throw new ParallelProcessException(
                            'Theme "' . $theme . '" should implements ' . ThemeInterface::class . '.'
                        );
                    }

                    $this->setTheme($themeObject);
                    break;
            }
        }

        return $this;
    }

    protected function defineRefreshIntervalFromInput(InputInterface $input): static
    {
        $interval = $input->getOption('refresh-interval');
        if (is_string($interval)) {
            if (is_numeric($interval) === false || (string) abs(intval($interval)) !== $interval) {
                throw new ParallelProcessException(
                    'Refresh interval "' . $interval . '" should be an integer value in microseconds.'
                );
            }

            $this->setRefreshInterval((int) $interval);
        }

        return $this;
    }

    protected function startProcesses(): static
    {
        return $this->startReadyProcesses();
    }

    protected function waitProcessesTermination(OutputInterface $output): static
    {
        $terminated = 0;
        while ($terminated < $this->getProcesses()->count()) {
            if ($this->isCanceled()) {
                break;
            }

            $terminated = 0;

            $this
                ->startReadyProcesses()
                ->defineCanceledProcesses();

            foreach ($this->getProcesses()->toArray() as $process) {
                if ($process->isCanceled() || $process->isTerminated()) {
                    $terminated++;
                }
            }

            $this->getTheme()->outputProcessesState($output, $this->getProcesses());

            if ($terminated < $this->getProcesses()->count()) {
                usleep($this->getRefreshInterval());
            }
        }

        return $this;
    }

    protected function defineCanceledProcesses(): static
    {
        foreach ($this->getProcesses()->getReady()->toArray() as $process) {
            if ($process->getStartCondition()->isCanceled()) {
                $process->setCanceled();
            }
        }

        return $this;
    }

    protected function startReadyProcesses(): static
    {
        $countRunningProcesses = $this->getProcesses()->countRunning();
        $maximumParallelProcesses = $this->getMaximumParallelProcesses();

        if (is_null($maximumParallelProcesses) || $countRunningProcesses < $maximumParallelProcesses) {
            foreach ($this->getProcesses()->getReady()->toArray() as $readyProcess) {
                if ($readyProcess->isCanceled() === false && $readyProcess->getStartCondition()->canBeStarted()) {
                    $readyProcess->start();
                }

                if (
                    is_int($maximumParallelProcesses)
                    && $this->getProcesses()->countRunning() >= $maximumParallelProcesses
                ) {
                    break;
                }
            }
        }

        return $this;
    }

    protected function getExitCode(): int
    {
        $return = static::SUCCESS;

        foreach ($this->getProcesses()->toArray() as $process) {
            if (
                (
                    (
                        $process->isCanceled()
                        && $process->isCanceledAsError()
                    ) || (
                        $process->isTerminated()
                        && $process->isSuccessful() === false
                    )
                ) && $process->isSpreadErrorToApplicationExitCode()
            ) {
                $return = static::FAILURE;
                break;
            }
        }

        return $return;
    }

    protected function createDefaultOutput(): OutputInterface
    {
        return new ConsoleBufferedOutput();
    }
}
