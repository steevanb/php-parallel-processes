---
hide:
  - toc
---

# Boostrap process

You can execute a process before any other process with a bootstrap process.

Bootstrap process can be added with `ParallelProcessesApplication::addProcess()`, 
and should be an instance of [BootstrapProcessInterface](https://github.com/steevanb/php-parallel-processes/blob/master/src/Process/BootstrapProcessInterface.php).

You can use [BootstrapProcess](https://github.com/steevanb/php-parallel-processes/blob/master/src/Process/BoostrapProcess.php),
who implements `BootstrapProcessInterface`,
instead of [Process](https://github.com/steevanb/php-parallel-processes/blob/master/src/Process/Process.php).

You can add this process when you want, before calling `ParallelProcessesApplication::run()`: before normal process, or after, it does not matter.

!!! info "Bootstrap process failure"
    If a bootstrap process fail, normal processes will not be executed.

    In this case, tear down processes will be executed.

```php hl_lines="10 12"
<?php

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\BootstrapProcess,
    Process\Process
};

(new ParallelProcessesApplication())
    ->addProcess(new BootstrapProcess(['bootstrap', 'process', '#1']))
    ->addProcess(new Process(['normal', 'process']))
    ->addProcess(new BootstrapProcess(['bootstrap', 'process', '#2']))
    ->run(new ArgvInput($argv));
```

!!! bug "Typo in BootstrapProcess class name before 1.1.0"
    Before 1.1.0, `BootstrapProcess` class name contains a typo: it was `BoostrapProcess`.

    It has been fixed in [1.1.0](https://github.com/steevanb/php-parallel-processes/blob/readthedocs/changelog.md):
    a new `BootstrapProcess` class has been created, and `BoostrapProcess` has been depreciated.

# Tear down process

You can execute a process after all other processes with a tear down process.

Tear down process can be added with `ParallelProcessesApplication::addProcess()`,
and should be an instance of [TearDownProcessInterface](https://github.com/steevanb/php-parallel-processes/blob/master/src/Process/TearDownProcessInterface.php).

You can use [TearDownProcess](https://github.com/steevanb/php-parallel-processes/blob/master/src/Process/TearDownProcess.php), 
who implements `TearDownProcessInterface`, 
instead of [Process](https://github.com/steevanb/php-parallel-processes/blob/master/src/Process/Process.php).

You can add this process when you want, before calling `ParallelProcessesApplication::run()`: before normal process, or after, it does not matter.

!!! info "Tear down process execution rule"
    The tear down processes will always be executed, whether the previous processes succeeded or failed.

```php hl_lines="10 12"
<?php

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process,
    Process\TearDownProcess
};

(new ParallelProcessesApplication())
    ->addProcess(new TearDownProcess(['tear', 'down', 'process', '#1']))
    ->addProcess(new Process(['normal', 'process']))
    ->addProcess(new TearDownProcess(['tear', 'down', 'process', '#2']))
    ->run(new ArgvInput($argv));
```
