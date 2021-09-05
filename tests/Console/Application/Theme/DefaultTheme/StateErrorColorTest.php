<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\Theme\DefaultTheme;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme;
use Symfony\Component\Console\Color;

final class StateErrorColorTest extends TestCase
{
    public function testDefaultValue(): void
    {
        $theme = new DefaultTheme();

        static::assertInstanceOf(Color::class, $theme->getStateErrorColor());
    }

    public function testSetter(): void
    {
        $color = new Color();

        static::assertSame(
            $color,
            (new DefaultTheme())
                ->setStateErrorColor($color)
                ->getStateErrorColor()
        );
    }
}