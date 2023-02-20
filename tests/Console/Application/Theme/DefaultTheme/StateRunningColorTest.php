<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\Theme\DefaultTheme;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme;
use Symfony\Component\Console\Color;

/**
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::__construct
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::setStateRunningColor
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::getStateRunningColor
 */
final class StateRunningColorTest extends TestCase
{
    public function testDefaultValue(): void
    {
        $theme = new DefaultTheme();

        $theme->getStateRunningColor();
        $this->addToAssertionCount(1);
    }

    public function testSetter(): void
    {
        $color = new Color();

        static::assertSame(
            $color,
            (new DefaultTheme())
                ->setStateRunningColor($color)
                ->getStateRunningColor()
        );
    }
}
