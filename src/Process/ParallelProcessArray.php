<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Process;

use steevanb\PhpTypedArray\ObjectArray\ObjectArray;

class ParallelProcessArray extends ObjectArray
{
    public function __construct(iterable $values = [])
    {
        parent::__construct($values, ParallelProcess::class);
    }

    public function current(): ?ParallelProcess
    {
        return parent::current();
    }
}
