<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests;

use Steevanb\ParallelProcess\Process\Process;

trait CreateLsProcessTrait
{
    private function createLsProcess(): Process
    {
        return new Process(['ls']);
    }
}
