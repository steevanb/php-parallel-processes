<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\Theme\DefaultTheme;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme;

/**
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::__construct
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::setStateCanceledIcon
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::getStateCanceledIcon
 */
final class StateCanceledIconTest extends TestCase
{
    public function testDefaultValue(): void
    {
        $theme = new DefaultTheme();

        static::assertSame('*', $theme->getStateCanceledIcon());
    }

    public function testSetter(): void
    {
        static::assertSame(
            '+',
            (new DefaultTheme())
                ->setStateCanceledIcon('+')
                ->getStateCanceledIcon()
        );
    }
}
