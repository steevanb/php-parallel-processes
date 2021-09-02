<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\Theme\DefaultTheme;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme;

final class StateReadyIconTest extends TestCase
{
    public function testDefaultValue(): void
    {
        $theme = new DefaultTheme();

        static::assertSame('>', $theme->getStateReadyIcon());
    }

    public function testSetter(): void
    {
        static::assertSame(
            '*',
            (new DefaultTheme())
                ->setStateReadyIcon('*')
                ->getStateReadyIcon()
        );
    }
}
