<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\Theme\DefaultTheme;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme;
use Symfony\Component\Console\Output\OutputInterface;

final class ExecutionTimeVerbosityTest extends TestCase
{
    public function testDefaultValue(): void
    {
        $theme = new DefaultTheme();

        static::assertSame(64, $theme->getExecutionTimeVerbosity());
    }

    public function testSetter(): void
    {
        static::assertSame(
            OutputInterface::VERBOSITY_VERY_VERBOSE,
            (new DefaultTheme())
                ->setExecutionTimeVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE)
                ->getExecutionTimeVerbosity()
        );
    }
}
