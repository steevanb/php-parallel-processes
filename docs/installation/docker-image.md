---
hide:
  - toc
---

# Use Docker image

`parallel-processses` provide Docker images with everything installed: PHP, the library and Docker.

You can choose between this 3 versions:

* `alpine`: smallest version, but could be "too much simple" sometimes
* `buster`: middle version, contains almost everything needed
* `bookworm`: larger version, should contain what you need

# Dependencies

<div class="grid cards" markdown>

- :material-penguin: __OS__: Linux, Windows and macOS
- :material-docker: __Docker__: you should already have a compatible version

</div>

# Basic usage

See [minimal configuration](../usage/minimal-configuration.md) to create your parallel processes configuration.

Then you can use official images to execute it:
```bash
docker \
    run \
        --rm \
        -it \
        -v "$(pwd)":/app \
        steevanb/php-parallel-processes:{{ package_version }}-alpine \
            php /app/parallel-processes.php
```

# Docker outside of Docker (DooD)

If your processes need to execute Docker commands on your host, you can do it with DooD.

You have to add a volume to your Docker socket, that's all:

```bash hl_lines="6"
docker \
    run \
        --rm \
        -it \
        -v "$(pwd)":/app \
        -v /var/run/docker.sock:/var/run/docker.sock \
        steevanb/php-parallel-processes:{{ package_version }}-alpine \
            php /app/parallel-processes.php 
```

!!! info "docker.sock host path"
    Use `docker context inspect` to get your socket path if it's not `/var/run/docker.sock`.
    
    In this case, keep `/var/run/docker.sock` for the volume target.

Examples of processes that use the Docker host's socket:
```php
<?php

use Steevanb\ParallelProcess\{
    Console\Application\ParallelProcessesApplication,
    Process\Process
};
use Symfony\Component\Console\Input\ArgvInput;

require $_ENV['COMPOSER_GLOBAL_AUTOLOAD_FILE_NAME'];

(new ParallelProcessesApplication())

    // This command will be executed in the parallel-processes Docker container,
    // but it will use the host's Docker socket
    // It’s almost as if it were executed directly on the host
    ->addProcess(new Process(['docker', 'build']))
    
    // You can use Docker compose plugin
    ->addProcess(new Process(['docker', 'compose', 'build']))
    
    // You can use Docker buildx plugin, but only with buster and bookworm images
    // alpine do not contain buildx
    ->addProcess(new Process(['docker', 'buildx', 'build']))
    
    ->run(new ArgvInput($argv));
```

Official images are bundled with Docker and some plugins:

| Image                                                                                                                               |                  docker                   |              compose plugin               |               buildx plugin               |
|-------------------------------------------------------------------------------------------------------------------------------------|:-----------------------------------------:|:-----------------------------------------:|:-----------------------------------------:|
| [{{ package_version }}-alpine](https://hub.docker.com/r/steevanb/php-parallel-processes/tags?name={{ package_version }}-alpine)     | :material-checkbox-marked:{ .icon-green } | :material-checkbox-marked:{ .icon-green } |                                           |
| [{{ package_version }}-buster](https://hub.docker.com/r/steevanb/php-parallel-processes/tags?name={{ package_version }}-buster)     | :material-checkbox-marked:{ .icon-green } | :material-checkbox-marked:{ .icon-green } | :material-checkbox-marked:{ .icon-green } |
| [{{ package_version }}-bookworm](https://hub.docker.com/r/steevanb/php-parallel-processes/tags?name={{ package_version }}-bookworm) | :material-checkbox-marked:{ .icon-green } | :material-checkbox-marked:{ .icon-green } | :material-checkbox-marked:{ .icon-green } |

!!! info "Docker is installed since 1.1.0"
    Docker, compose and buildx plugins are installed in Docker images since `1.1.0`.

# Available versions

See [Docker tags](https://hub.docker.com/r/steevanb/php-parallel-processes/tags).

See [changelog](https://github.com/steevanb/php-parallel-processes/blob/master/changelog.md).
