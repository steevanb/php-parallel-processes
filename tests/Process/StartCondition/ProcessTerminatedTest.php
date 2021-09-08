<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\StartCondition;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\StartCondition,
    Tests\CreateLsProcessTrait
};

final class ProcessTerminatedTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testAdd(): void
    {
        $startCondition = new StartCondition();
        $process = $this->createLsProcess();

        static::assertTrue($startCondition->getProcessesTerminated()->isReadOnly());
        $startCondition->addProcessTerminated($process);
        static::assertTrue($startCondition->getProcessesTerminated()->isReadOnly());

        static::assertCount(1, $startCondition->getProcessesTerminated());
        static::assertSame($process, $startCondition->getProcessesTerminated()[0]);
    }
}
