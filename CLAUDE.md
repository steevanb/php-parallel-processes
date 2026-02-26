# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

PHP library for running shell processes in parallel with dependency management between them. Built on Symfony Console/Process components. The library itself is used to run its own CI validation (self-dogfooding).

## Common Commands

**Run tests locally (requires PHP 8.2+ and ext-pcntl):**
```bash
composer install
vendor/bin/phpunit -c config/ci/phpunit.xml
```

**Run full CI validation (runs all checks in parallel using this library itself):**
```bash
bin/ci/env          # install dependencies
bin/ci/validate     # runs phpcs, phpstan, phpunit, composer checks, etc. in parallel
```

**Run individual CI tools:**
```bash
bin/ci/phpcs                # code style (steevanb custom sniffs)
bin/ci/phpstan              # static analysis (level 9)
bin/ci/phpunit-coverage     # tests with coverage
```

CI tests across a matrix: PHP 8.2/8.3/8.4 × Symfony 7.0/7.1/7.2/7.3.

## Architecture

### Core Components

**`ParallelProcessesApplication`** (`src/Console/Application/`) — Main orchestrator. Extends Symfony `SingleCommandApplication`, implements `SignalableCommandInterface`. Uses a fluent builder interface to add processes, configure timeout/refresh interval/max parallel processes, then `run()`. Handles SIGINT for graceful shutdown.

**`Process`** (`src/Process/Process.php`) — Extends Symfony `Process`, implements `ProcessInterface`. Each process has configurable output verbosity levels (standard, error, failure, canceled), a `StartCondition` for dependency management, and cancellation support.

**`StartCondition`** (`src/Process/StartCondition.php`) — Controls when a process can start via three dependency collections:
- `processesTerminated` — start after these finish (regardless of success/failure)
- `processesSuccessful` — start only after these succeed
- `processesFailed` — start only after these fail

**`BootstrapProcess` / `TearDownProcess`** — Special process types. Bootstrap processes automatically become dependencies (via `processesSuccessful`) for all standard processes. TearDown processes automatically wait for all standard processes to terminate.

**Theme system** (`src/Console/Application/Theme/`) — Strategy pattern. `DefaultTheme` shows real-time colored status; `SummaryTheme` shows minimal output (used in CI). Custom themes implement `ThemeInterface`. Selected via `--theme` CLI option.

**`ProcessInterfaceCollection`** — Type-safe collection with filtering: `getReady()`, `getStarted()`, `countRunning()`.

## Code Conventions

- `declare(strict_types=1)` on every file
- PHP 8.2+ features: constructor property promotion, `readonly`, enums, `#[\Override]` attribute
- PHPStan level 9 (strictest)
- Fluent interface pattern (methods return `static`)
- Interfaces suffixed with `Interface`, no `Abstract` prefix convention
- Tests: one test class per method, `@covers` annotation required, test directory mirrors `src/` structure
- PHPUnit config: `config/ci/phpunit.xml` (with coverage), `config/release/phpunit.xml`
- PHPStan configs: `config/ci/phpstan/` (per PHP/Symfony version combination)
