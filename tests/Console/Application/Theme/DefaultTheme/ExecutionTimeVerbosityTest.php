<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\Theme\DefaultTheme;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::__construct
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::setExecutionTimeVerbosity
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::getExecutionTimeVerbosity
 */
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
            128,
            (new DefaultTheme())
                ->setExecutionTimeVerbosity(128)
                ->getExecutionTimeVerbosity()
        );
    }
}
