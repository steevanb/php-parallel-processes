<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\ParallelProcessesApplication;

use Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication;

class TestParallelProcessesApplication extends ParallelProcessesApplication
{
    public function callConfigureBootstrapProcesses(): static
    {
        return parent::configureBootstrapProcesses();
    }

    public function callConfigureTearDownProcesses(): static
    {
        return parent::configureTearDownProcesses();
    }
}
