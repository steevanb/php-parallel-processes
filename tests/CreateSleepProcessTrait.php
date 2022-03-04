<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests;

use Steevanb\ParallelProcess\Process\Process;

trait CreateSleepProcessTrait
{
    private function createSleepProcess(): Process
    {
        return new Process(['sleep', '1']);
    }
}
