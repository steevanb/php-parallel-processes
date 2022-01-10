<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\Theme\DefaultTheme;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme;
use Symfony\Component\Console\Color;

/**
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::__construct
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::setStateReadyColor
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::getStateReadyColor
 */
final class StateReadyColorTest extends TestCase
{
    public function testDefaultValue(): void
    {
        $theme = new DefaultTheme();

        static::assertInstanceOf(Color::class, $theme->getStateReadyColor());
    }

    public function testSetter(): void
    {
        $color = new Color();

        static::assertSame(
            $color,
            (new DefaultTheme())
                ->setStateReadyColor($color)
                ->getStateReadyColor()
        );
    }
}
