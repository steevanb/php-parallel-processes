[![Version](https://img.shields.io/badge/version-1.1.0-blueviolet.svg)](https://github.com/steevanb/php-parallel-processes/tree/1.1.0)
[![PHP](https://img.shields.io/badge/php-^8.1-blue.svg)](https://php.net)
![Lines](https://img.shields.io/badge/code%20lines-6,388-blue.svg)
![Downloads](https://poser.pugx.org/steevanb/php-parallel-processes/downloads)
![GitHub workflow status](https://img.shields.io/github/actions/workflow/status/steevanb/php-parallel-processes/ci.yml?branch=master)
![Coverage](https://img.shields.io/badge/coverage-46%25-success.svg)

# php-parallel-processes

Execute processes in parallel.

Examples of use: start your environment, CI tools...

# Installation

## Use official Docker image

You can use the official Docker image to not install anything:
[steevanb/php-parallel-processes:x.y.z](https://hub.docker.com/r/steevanb/php-parallel-processes/tags).

Example:
```bash
docker \
    run \
        --rm \
        --tty \
        --interactive \
        --volume "$(pwd)":"$(pwd)" \
        --workdir "$(pwd)" \
        steevanb/php-parallel-processes:x.y.z \
        php parallel-processes.php
```

### If your processes use docker

If processes in `parallel-processes.php` use Docker, you have to add a volume on your Docker socket:
```bash
--volume /var/run/docker.sock:/var/run/docker.sock
```

All official `php-parallel-processes` images have Docker and Docker compose installed, so you only need to add a volume on the socket.

### alpine, bookworm and buster

3 Docker images are provided for each `php-parallel-processes` version, use the one you want depending on your needs:
 * `alpine`: smaller version, but could be "too much simple" sometimes
 * `buster`: middle version, contains almost everything needed
 * `bookworm`: larger version, should contain what you need

## Install as Composer dependency

If you want to add `php-parallel-processes` directly in your project:

```bash
composer require steevanb/php-parallel-processes ^1.1
```

# Create processes configuration

You need to create a configuration for your processes, written in PHP.

Example: [bin/start.php](bin/start.php)

## Basic example

```php
<?php

declare(strict_types=1);

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process
};
use Symfony\Component\Console\Input\ArgvInput;

# If you use the official Docker image, you should use the Composer global autoload
require $_ENV['COMPOSER_GLOBAL_AUTOLOAD_FILE_NAME'];
# If you use the Composer dependency version, you should use your Composer autoload
require __DIR__ . '/vendor/autoload.php';

(new ParallelProcessesApplication())
    ->addProcess((new Process(['first', 'process']))->setName('First process'))
    ->addProcess((new Process(['second', 'process']))->setName('Second process'))
    ->run(new ArgvInput($argv));
```

## Configurations

### Global timeout


You can configure the global timeout with `ParallelProcessesApplication::setTimeout()`.

Default value: `null` (no timeout).

```php
(new ParallelProcessesApplication())
    // Timeout is in seconds, here we will have a 10s timeout
    ->setTimeout(10)
```

### Refresh interval

You can configure the refresh interval, to scan all processes status and start the next ones,
with `ParallelProcessesApplication::setRefreshInterval()`.

Default value: `10000` (10ms)

```php
(new ParallelProcessesApplication())
    // Timeout is in microseconds, here we will have a 500ms timeout
    ->setRefreshInterval(50000)
```

### Maximum processes in parallel

You can configure the maximum number of processes in parallel
with `ParallelProcessesApplication::setMaximumParallelProcesses()`.

Default value: `null` (no maximum)

```php
(new ParallelProcessesApplication())
    // Here we will have maximum 3 processes in parallel
    ->setMaximumParallelProcesses(3)
```

### Theme

`php-parallel-processes` comes with 2 themes:
 * [default](src/Console/Application/Theme/DefaultTheme.php)
   * Output everything
   * Use verbosity (`-v`, `-vv` or `-vvv`) to add execution time and process outputs
   * Should be used when you need to see live processes status, most of the time ;)
 * [summary](src/Console/Application/Theme/SummaryTheme.php)
   * Output only the start and the end of parallel processes
   * Use verbosity (`-v`, `-vv` or `-vvv`) to add execution time and process outputs
   * Should be used when you don't need to see live processes status, in CI for example

Configure it with `ParallelProcessesApplication::setTheme()`:
```php
use Steevanb\ParallelProcess\Console\Application\Theme\DefaultTheme();

(new ParallelProcessesApplication())
    ->setTheme(new DefaultTheme())
```

You can also configure it in CLI with `--theme` (CLI override PHP configuration):
```bash
php parallel-processes.php --theme summary
```

You can create your own theme by implementing [ThemeInterface](src/Console/Application/Theme/ThemeInterface.php).
