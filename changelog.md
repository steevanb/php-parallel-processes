### master

- Fix `StartCondition::isCanceled()` when a previous process has been canceled
- Add `SummaryTheme`
- Add `--theme` option
- Add `--refresh-interval` option

### [0.7.2](../../compare/0.7.1...0.7.2) - 2022-03-08

- Fix start ready processes when maximum paralell processes is not configured or value is `null`

### [0.7.1](../../compare/0.7.0...0.7.1) - 2022-03-07

- Add `pcntl` into Docker images

### [0.7.0](../../compare/0.6.0...0.7.0) - 2022-03-07

- Fix start ready processes when maximum paralell processes is configured
- Take `CTRL + C` into account

### [0.6.0](../../compare/0.5.0...0.6.0) - 2022-03-04

- Remove `parallel-processes` in Docker image name
- Create `-buster` and `-alpine` Docker images
- Fix `bin/release/phpunit-coverage` cache path
- Add `ParallelProcessesApplication::setMaximumParallelProcesses()` and `getMaximumParallelProcesses()`
- Add `ProcessArray::countRunning()`

### [0.5.0](../../compare/0.4.0...0.5.0) - 2022-02-14

- Add `ProcessFactory::createRemoveFileProcess()`

### [0.4.0](../../compare/0.3.0...0.4.0) - 2022-02-14

- Add `ParallelProcessesApplication::hasProcess()`
- Add `ProcessFactory`
- Add `ProcessFactory::createRemoveDirectoryProcess()`
- Fix PHPUnit cache file path in `bin/ci/phpunit-coverage`

### [0.3.0](../../compare/0.2.1...0.3.0) - 2022-01-10

- Create GitHub Actions to build and push Docker image `steevanb/php-parallel-processes:ci`
- Create GitHub Actions to build and push Docker image `steevanb/php-parallel-processes:parallel-processes-x.y.z` and automatically call it on new release
- Create GitHub Actions to build and push Docker image `steevanb/php-parallel-processes:release`
- Create configurations for Docker images name
- Create configuration for repository version
- Create configuration for Composer version
- Rework bin/ci/shellcheck to find files to check instead of having a list
- Update phpstan, add some plugins and run it for PHP 7.4 and 8.0
- Use parallel-processes in `bin/start`
- Allow `symfony/console` and `symfony/process` `^6.0`
- Add `PHP 8.1` compatibility

### [0.2.1](../../compare/0.2.0...0.2.1) - 2022-01-08

- Fix `bin/release/code-lines` who find code lines in `.idea` (directory of PHPStorm)
- Fix output who was not fully rewritten sometimes

### [0.2.0](../../compare/0.1.0...0.2.0) - 2021-09-11

- Rework binaries
- Update `composer require` documentation in `README.md` in GitHub Actions workflow `Release`

### [0.1.0](../../compare/0.0.0...0.1.0) - 2021-09-11

- Add `infection` in Docker image `steevanb/php-parallel-processes:release`

### 0.0.0 - 2021-09-09

- Create `ParallelProcessesApplication`
- Create `ThemeInterface and DefaultTheme`
- Create `ConsoleBufferedOutput`
- Create `ParallelProcessException`
- Create `Process`, `ProcessArray` and `StartCondition`
- Create GitHub Actions CI
- Create GitHub Actions workflow `Release`
