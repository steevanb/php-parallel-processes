### master

- Create GitHub Actions to build and push Docker image `steevanb/php-parallel-processes:ci`
- Create GitHub Actions to build and push Docker image `steevanb/php-parallel-processes:parallel-processes-x.y.z` and automatically call it on new release
- Create GitHub Actions to build and push Docker image `steevanb/php-parallel-processes:release`
- Create configurations for Docker images name
- Create configuration for repository version
- Create configuration for Composer version
- Rework bin/ci/shellcheck to find files to check instead of having a list
- Update phpstan, add some plugins and run it for PHP 7.4 and 8.0
- Use parallel-processes in `bin/start`

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
