---
hide:
  - toc
---

# Minimal configuration

Here is an example of the minimal configuration, to have 2 processes running in parallel:

```php
<?php

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process
};
use Symfony\Component\Console\Input\ArgvInput;

# If you use the official Docker image,
# you should use the Composer global autoload
require $_ENV['COMPOSER_GLOBAL_AUTOLOAD_FILE_NAME'];
# If you use the Composer dependency version,
# you should use your Composer autoload
require __DIR__ . '/vendor/autoload.php';

(new ParallelProcessesApplication())
    ->addProcess(new Process(['first', 'process']))
    ->addProcess(new Process(['second', 'process']))
    ->run(new ArgvInput($argv));
```

# Advanced usage

See [application configuration](application-configuration.md).

See [process configuration](process-configuration/miscellaneous.md).
