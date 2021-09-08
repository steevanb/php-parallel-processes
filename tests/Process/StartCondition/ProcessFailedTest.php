<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\StartCondition;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\StartCondition,
    Tests\CreateLsProcessTrait
};

final class ProcessFailedTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testAdd(): void
    {
        $startCondition = new StartCondition();
        $process = $this->createLsProcess();

        static::assertTrue($startCondition->getProcessesFailed()->isReadOnly());
        $startCondition->addProcessFailed($process);
        static::assertTrue($startCondition->getProcessesFailed()->isReadOnly());

        static::assertCount(1, $startCondition->getProcessesFailed());
        static::assertSame($process, $startCondition->getProcessesFailed()[0]);
    }
}
