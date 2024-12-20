### master

### [1.0.1](../../compare/1.0.0...1.0.1) - 2024-12-02

- Fix dependency `symfony/console` version to `^7.0`

### [1.0.0](../../compare/0.15.0...1.0.0) - 2024-11-28

### [0.15.0](../../compare/0.14.0...0.15.0) - 2024-10-24

- [BC Break] Remove `StartCondition::addProcessTerminated()`, `addProcessSuccessful()` and `addProcessFailed()`: use `getProcesses[Terminated|Successful|Failed]()->add()` instead
- Update `steevanb/php-collection` dependency to `^6.0`
- Remove readonly mode in collections

### [0.14.0](../../compare/0.13.0...0.14.0) - 2024-10-22

- Add `-bookworm` Docker image
- Update PHP from 8.1 to 8.3 in `-alpine` Docker image
- Update PHP from 8.1 to 8.2 in `-buster` Docker image
- Remove Symfony < 7.0 support, because of BC break in `SignalableCommandInterface::handleSignal()`
- Remove PHP 8.1 support, because Symfony 7.0 required PHP >= 8.1

### [0.13.0](../../compare/0.12.0...0.13.0) - 2023-06-01

- [#130](https://github.com/steevanb/php-parallel-processes/issues/130) Rework execution time output in `DefaultTheme`
- [#236](https://github.com/steevanb/php-parallel-processes/issues/236) Rework verbosity for `DefaultTheme`
- [#237](https://github.com/steevanb/php-parallel-processes/issues/237) Fix `getStandardOutputVerbosity()` and `getErrorOutputVerbosity()`

### [0.12.0](../../compare/0.11.0...0.12.0) - 2023-06-01

- [#227](https://github.com/steevanb/php-parallel-processes/issues/227) Fix TearDownProcess who never start in some cases
- [#226](https://github.com/steevanb/php-parallel-processes/issues/226) Add compatibility with Symfony 6.3
- [#228](https://github.com/steevanb/php-parallel-processes/issues/228) Execute phpstan for each Symfony version
- [#224](https://github.com/steevanb/php-parallel-processes/issues/224) Add global timeout with ParallelProcessesApplication::setTimeout()
- **[BC Break]** [#215](https://github.com/steevanb/php-parallel-processes/issues/215) Replace dependency `steevanb/php-typed-array` by `steevanb/php-collection`
- **[BC Break]** Add `ProcessInterface::stop()` (it should not be a problem as it's a method already asked by symfony/process interface)
- Use `ProcessInterface` as argument instead of `Process` everywhere

### [0.11.0](../../compare/0.10.0...0.11.0) - 2023-04-05

- **[BC Break]** Replace `ProcessArray` by `ProcessInterfaceArray`
- Add `ProcessInterface`, `BootstrapProcessInterface` and `TearDownProcessInterface`
- Add `BootstrapProcess` and `TearDownProcess`
- **[BC Break]** Add ProcessInterface::getOutputStatePrefix() and getOutputSummaryPrefix()
- Add Process::setOutputStatePrefix(), getOutputStatePrefix(), setOutputSummaryPrefix(), getOutputSummaryPrefix() and setOutputPrefix()

### [0.10.0](../../compare/0.9.0...0.10.0) - 2023-02-21

- Update [steevanb/php-typed-array](https://github.com/steevanb/php-typed-array) to 4.0

### [0.9.0](../../compare/0.8.3...0.9.0) - 2023-02-20

- Remove compatibility with PHP 7.4 and 8.0
- Remove compatibility with Symfony 5 and 6.0
- Replace return type `self` by `static` everywhere
- Update CI tools

### [0.8.3](../../compare/0.8.2...0.8.3) - 2022-03-29

- Add `jq` in `-buster` Docker images (since `0.8.3`)

### [0.8.2](../../compare/0.8.1...0.8.2) - 2022-03-15

- Fix `SummaryTheme` processes count color

### [0.8.1](../../compare/0.8.0...0.8.1) - 2022-03-15

- Fix `--no-ansi`

### [0.8.0](../../compare/0.7.2...0.8.0) - 2022-03-14

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
