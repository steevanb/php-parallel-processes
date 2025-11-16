---
hide:
  - toc
---

# Output verbosity

By default, default theme will output only the state, the process name and the process error output.

You can add information, depending on the verbosity level.

| Output                  |                  Normal                   |                  Verbose                  |               Very verbose                | Debug                                     |
|-------------------------|:-----------------------------------------:|:-----------------------------------------:|:-----------------------------------------:|:-----------------------------------------:|
| Process state           | :material-checkbox-marked:{ .icon-green } | :material-checkbox-marked:{ .icon-green } | :material-checkbox-marked:{ .icon-green } | :material-checkbox-marked:{ .icon-green } |
| Process name            | :material-checkbox-marked:{ .icon-green } | :material-checkbox-marked:{ .icon-green } | :material-checkbox-marked:{ .icon-green } | :material-checkbox-marked:{ .icon-green } |
| Execution time          |                                           | :material-checkbox-marked:{ .icon-green } | :material-checkbox-marked:{ .icon-green } | :material-checkbox-marked:{ .icon-green } |
| Process standard output |                                           |                                           | :material-checkbox-marked:{ .icon-green } | :material-checkbox-marked:{ .icon-green } |
| Process error output    | :material-checkbox-marked:{ .icon-green } | :material-checkbox-marked:{ .icon-green } | :material-checkbox-marked:{ .icon-green } | :material-checkbox-marked:{ .icon-green } |

# Configure verbosity level

Verbosity level is configured on the CLI command with `-v`, `-vv` or `-vvv`.

**Normal**: default level, do not add anything.
```bash
php parallel-process.php
```

**Verbose**: add `-v`.
```bash
php parallel-process.php -v
```

**Very verbose**: add `-vv`.
```bash
php parallel-process.php -vv
```

**Debug**: add `-vvv`.
```bash
php parallel-process.php -vvv
```
