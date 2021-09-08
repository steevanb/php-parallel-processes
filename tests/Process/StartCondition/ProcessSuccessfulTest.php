<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Process\StartCondition;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Process\StartCondition,
    Tests\CreateLsProcessTrait
};

final class ProcessSuccessfulTest extends TestCase
{
    use CreateLsProcessTrait;

    public function testAdd(): void
    {
        $startCondition = new StartCondition();
        $process = $this->createLsProcess();

        static::assertTrue($startCondition->getProcessesSuccessful()->isReadOnly());
        $startCondition->addProcessSuccessful($process);
        static::assertTrue($startCondition->getProcessesSuccessful()->isReadOnly());

        static::assertCount(1, $startCondition->getProcessesSuccessful());
        static::assertSame($process, $startCondition->getProcessesSuccessful()[0]);
    }
}
