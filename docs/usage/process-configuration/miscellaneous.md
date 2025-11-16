---
hide:
  - toc
---

# Name

Default: Last word of the command.

Example with `new Process(['/foo', 'bar', 'baz'])`: name will be `baz`.

```php
<?php

use Steevanb\ParallelProcess\Process\Process;

(new Process(['/command/part/default-name']))->setName('foo');
```

# Canceled as error

You can configure if the cancelation of a process is considered as an error (`1`) or not (`0`) for the exit code.

Default: `true`.

```php
<?php

use Steevanb\ParallelProcess\Process\Process;
use Symfony\Component\Console\Output\OutputInterface;

(new Process(['command']))->setCanceledAsError(true);
```

See [example](https://github.com/steevanb/php-parallel-processes/tree/master/examples/usage/process-configuration/canceled-as-error.php).

!!! example "Try it!"

    With `setCanceledAsError(true)` (default value):
    ```bash
    PP_CANCELED_AS_ERROR=true parallel-processes-example.sh usage/process-configuration/canceled-as-error.php ; echo "Exit code: $?"
    ```
    With `setCanceledAsError(false)`:
    ```bash
    PP_CANCELED_AS_ERROR=false parallel-processes-example.sh usage/process-configuration/canceled-as-error.php ; echo "Exit code: $?"
    ```
