<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\Theme\DefaultTheme;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme;
use Symfony\Component\Console\Color;

/**
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::__construct
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::setStateCanceledColor
 * @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::getStateCanceledColor
 */
final class StateCanceledColorTest extends TestCase
{
    public function testDefaultValue(): void
    {
        static::assertEquals(
            new Color('black', 'yellow'),
            (new DefaultTheme())->getStateCanceledColor()
        );
    }

    public function testSetter(): void
    {
        $color = new Color();

        static::assertSame(
            $color,
            (new DefaultTheme())
                ->setStateCanceledColor($color)
                ->getStateCanceledColor()
        );
    }
}
