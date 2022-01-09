<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests;

use Steevanb\ParallelProcess\Process\Process;

trait GetReflectionClosureTrait
{
    protected function getReflectionClosure(\ReflectionMethod $reflectionMethod, Process $process): \Closure
    {
        $return = $reflectionMethod->getClosure($process);
        if ($return instanceof \Closure === false) {
            throw new \Exception('Closure not found for ' . $reflectionMethod->getName() . '.');
        }

        return $return;
    }
}
