<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\Theme\DefaultTheme;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme;

/**
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::__construct
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::setStateRunningIcon
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::getStateRunningIcon
 */
final class StateRunningIconTest extends TestCase
{
    public function testDefaultValue(): void
    {
        $theme = new DefaultTheme();

        static::assertSame('▶', $theme->getStateRunningIcon());
    }

    public function testSetter(): void
    {
        static::assertSame(
            '+',
            (new DefaultTheme())
                ->setStateRunningIcon('+')
                ->getStateRunningIcon()
        );
    }
}
