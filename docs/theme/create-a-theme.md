---
hide:
  - toc
---

# Create a theme

To create a theme, you have to create a class who implements 
[ThemeInterface](https://github.com/steevanb/php-parallel-processes/blob/master/src/Console/Application/Theme/ThemeInterface.php).

## ThemeInterface::outputStart()

This method will be called before starting the processes.

You can output headers here for example.

## ThemeInterface::outputProcessesState()

This method will be called at each [application refresh interval](../usage/application-configuration.md), to output processes state.

## ThemeInterface::outputSummary()

This method will be called after all processes are terminated.

For example, you can take verbosity into account here to write processes outputs, add a footer etc.

## Example

See [DefaultTheme](https://github.com/steevanb/php-parallel-processes/blob/master/src/Console/Application/Theme/DefaultTheme.php).

# How to use it

## Configure it in PHP

Like Default and Summary themes, call `ParallelProcessesApplication::setTheme()` with your theme instance:

```php
<?php

use Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication;

(new ParallelProcessesApplication())->setTheme(new FooTheme());
```

## Configure it in CLI

Like Default and Summary theme, add `--theme` in your CLI command, the value should be the FQCN of your theme:

```bash
php parallel-process.php --theme=App\\FooTheme
```

!!! info "Creation or the theme object"

    Your theme class should not have parameters in `__construct()` to be used in CLI.

    See [ParallelProcessesApplication::defineThemeFromInput()](https://github.com/steevanb/php-parallel-processes/blob/master/src/Console/Application/ParallelProcessesApplication.php).

    If you need a different behavior, do not hesitate to contribute!
