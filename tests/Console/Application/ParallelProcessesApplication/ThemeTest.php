<?php

declare(strict_types=1);

namespace Steevanb\ParallelProcess\Tests\Console\Application\ParallelProcessesApplication;

use PHPUnit\Framework\TestCase;
use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Console\Application\Theme\DefaultTheme
};

class ThemeTest extends TestCase
{
    public function testSetTheme(): void
    {
        $theme = new DefaultTheme();
        $application = (new ParallelProcessesApplication())
            ->setTheme($theme);

        static::assertSame(spl_object_hash($theme), spl_object_hash($application->getTheme()));
    }
}
