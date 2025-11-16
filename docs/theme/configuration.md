---
hide:
  - toc
---

# Themes

`parallel-processes` contains 2 themes: `Default` and `Summary`.

## Default theme

This theme output processes status at regular interval, configured by [application refresh interval](../usage/application-configuration.md).

The ouput is rewritten at each refresh interval.

This theme is the default one, and should be used most of the time.

## Summary theme

This theme output only one line at the start: `Starting X processes...`.

Processes status are not showned / refreshed.

At the end, all processes status and output are written, only one time.

This theme should be used in environments who can't rewrite output, like CI : GitHub Actions logs for example.

# Configure a theme

## In PHP configuration

You can configure the theme in your PHP configuration:

```php
<?php

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Console\Application\Theme\DefaultTheme,
    Console\Application\Theme\SummaryTheme
};

(new ParallelProcessesApplication())
    # Configure the theme to Default (default value)
    ->setTheme(new Defaultheme())
    # Configure the theme to Summary
    ->setTheme(new SummaryTheme())
```

## In CLI

You can configure the theme in CLI.

`Default` theme (default value):

```bash
php parallel-process.php --theme=default
```

`Summary` theme:

```bash
php parallel-process.php --theme=summary
```

!!! info "CLI override PHP"

    The theme configured in the CLI overrides the one configured in PHP.
