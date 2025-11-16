---
hide:
  - toc
---

# State line prefix

You can add a prefix before the state line only.

Default: `null`.

```php
<?php

use Steevanb\ParallelProcess\Process\Process;

(new Process(['command']))->setOutputStatePrefix('foo');
```

See [example](https://github.com/steevanb/php-parallel-processes/tree/master/examples/usage/process-configuration/output-state-prefix.php).

!!! example "Try it!"

    ```bash
    parallel-processes-example.sh usage/process-configuration/output-state-prefix.php
    ```

# Summary lines prefix

You can add a prefix before process output lines.

Default: `null`.

```php
<?php

use Steevanb\ParallelProcess\Process\Process;

(new Process(['command']))->setOutputSummaryPrefix('foo');
```

See [example](https://github.com/steevanb/php-parallel-processes/tree/master/examples/usage/process-configuration/output-summary_prefix.php).

!!! info "Indentation"

    Indentation will be added after the configured summary prefix, to show output lines are for this process.

!!! example "Try it!"

    ```bash
    parallel-processes-example.sh usage/process-configuration/output-summaryprefix.php
    ```

# All lines

You can add an output prefix before any line.

It's a shortcut for calling `Process::setOutputStatePrefix()` then `Process::setOutputSummaryPrefix()` with the same prefix.

Default: `null`.

```php
<?php

use Steevanb\ParallelProcess\Process\Process;

(new Process(['command']))->setOutputPrefix('foo');
```

See [example](https://github.com/steevanb/php-parallel-processes/tree/master/examples/usage/process-configuration/output-prefix.php).

!!! example "Try it!"

    ```bash
    parallel-processes-example.sh usage/process-configuration/output-prefix.php
    ```
