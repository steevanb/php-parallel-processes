<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\Theme\DefaultTheme;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme;
use Symfony\Component\Console\Color;

/** @covers \Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme::__construct */
final class ConstructTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $theme = new DefaultTheme();

        static::assertInstanceOf(Color::class, $this->getProtectedPropertyValue($theme, 'stateReadyColor'));
        static::assertSame('>', $this->getProtectedPropertyValue($theme, 'stateReadyIcon'));

        static::assertInstanceOf(Color::class, $this->getProtectedPropertyValue($theme, 'stateCanceledColor'));
        static::assertSame('*', $this->getProtectedPropertyValue($theme, 'stateCanceledIcon'));

        static::assertInstanceOf(Color::class, $this->getProtectedPropertyValue($theme, 'stateRunningColor'));
        static::assertSame('▶', $this->getProtectedPropertyValue($theme, 'stateRunningIcon'));

        static::assertInstanceOf(Color::class, $this->getProtectedPropertyValue($theme, 'stateSuccessfulColor'));
        static::assertSame('✓', $this->getProtectedPropertyValue($theme, 'stateSuccessfulIcon'));

        static::assertInstanceOf(Color::class, $this->getProtectedPropertyValue($theme, 'stateErrorColor'));
        static::assertSame('✘', $this->getProtectedPropertyValue($theme, 'stateErrorIcon'));

        static::assertSame(64, $this->getProtectedPropertyValue($theme, 'executionTimeVerbosity'));
    }

    /** @return mixed */
    private function getProtectedPropertyValue(DefaultTheme $theme, string $property)
    {
        $reflection = new \ReflectionProperty($theme, $property);

        return $reflection->getValue($theme);
    }
}
