<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\Theme\DefaultTheme;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme;

/**
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::__construct
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::setStateErrorIcon
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::getStateErrorIcon
 */
final class StateErrorIconTest extends TestCase
{
    public function testDefaultValue(): void
    {
        $theme = new DefaultTheme();

        static::assertSame('✘', $theme->getStateErrorIcon());
    }

    public function testSetter(): void
    {
        static::assertSame(
            '+',
            (new DefaultTheme())
                ->setStateErrorIcon('+')
                ->getStateErrorIcon()
        );
    }
}
