---
hide:
  - toc
---

# Global timeout

Default value: `null` (no timeout).

Configured timeout is in seconds.

```php
<?php

use Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication;

(new ParallelProcessesApplication())
    // Timeout is in seconds, here we will have a 10s timeout
    ->setTimeout(10)
```

# Refresh interval

You can configure the refresh interval, to scan all processes status and start the next ones,
with `ParallelProcessesApplication::setRefreshInterval()`.

Default value: `10000` (10ms).

Configured refresh interval is in microseconds.

```php
<?php

use Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication;

(new ParallelProcessesApplication())
    // Refresh interval is in microseconds, here we will have a 500ms timeout
    ->setRefreshInterval(50000)
```

# Maximum processes in parallel

Default value: `null` (no maximum).

```php
<?php

use Steevanb\ParallelProcess\Console\Application\ParallelProcessesApplication;

(new ParallelProcessesApplication())
    // Here we will have maximum 3 processes in parallel
    ->setMaximumParallelProcesses(3)
```

# Theme

See [Configure a theme](../theme/configuration.md).
