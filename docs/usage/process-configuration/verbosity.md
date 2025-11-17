---
hide:
  - toc
---

# Output verbosity

All processes outputs will be written at the end of all processes execution.

Nothing will be written during execution.

See [verbosity](../output-verbosity.md).

# Standard output verbosity

Default: `OutputInterface::VERBOSITY_VERY_VERBOSE` (`-vv`).

```php
<?php

use Steevanb\ParallelProcess\Process\Process;
use Symfony\Component\Console\Output\OutputInterface;

(new Process(['command']))->setStandardOutputVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);
```

# Error output verbosity

Default: `OutputInterface::VERBOSITY_VERY_VERBOSE` (`-vv`).

```php
<?php

use Steevanb\ParallelProcess\Process\Process;
use Symfony\Component\Console\Output\OutputInterface;

(new Process(['command']))->setErrorOutputVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);
```

# Canceled output verbosity

Default: `OutputInterface::VERBOSITY_VERY_VERBOSE` (`-vv`).

```php
<?php

use Steevanb\ParallelProcess\Process\Process;
use Symfony\Component\Console\Output\OutputInterface;

(new Process(['command']))->setCanceledOutputVerbosity(OutputInterface::VERBOSITY_VERY_VERBOSE);
```

# Standard output verbosity when the process fails

Default: `OutputInterface::VERBOSITY_NORMAL`.

```php
<?php

use Steevanb\ParallelProcess\Process\Process;
use Symfony\Component\Console\Output\OutputInterface;

(new Process(['command']))->setFailureStandardOutputVerbosity(OutputInterface::VERBOSITY_NORMAL);
```

# Error output verbosity when the process fails

Default: `OutputInterface::VERBOSITY_NORMAL`.

```php
<?php

use Steevanb\ParallelProcess\Process\Process;
use Symfony\Component\Console\Output\OutputInterface;

(new Process(['command']))->setFailureErrorOutputVerbosity(OutputInterface::VERBOSITY_NORMAL);
```
