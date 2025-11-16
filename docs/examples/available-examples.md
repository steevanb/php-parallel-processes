---
hide:
  - toc
---

# Available examples

All examples are in [examples/](https://github.com/steevanb/php-parallel-processes/tree/master/examples).

# How to execute an example

You need to download the latest version of [parallel-processes-example.sh](https://raw.githubusercontent.com/steevanb/php-parallel-processes/refs/heads/master/examples/parallel-processes-example.sh).

`parallel-processes-example.sh` has one mandatory argument: the path to the example, after `examples/` directory:
```bash
parallel-processes-example.sh path/to/example.php
```

# Configure an example

Some of them can be configured with env vars.

All configuration are prefixed by `PP_`.

```bash hl_lines="1"
PP_CANCELED_AS_ERROR=true \
    parallel-processes-example.sh usage/process-configuration/canceled-as-error.php
```
