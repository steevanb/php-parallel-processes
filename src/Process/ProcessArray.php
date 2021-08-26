<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Process;

use steevanb\PhpTypedArray\ObjectArray\ObjectArray;

class ProcessArray extends ObjectArray
{
    public function __construct(iterable $values = [])
    {
        parent::__construct($values, Process::class);
    }

    public function current(): ?Process
    {
        return parent::current();
    }
}
