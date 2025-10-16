---
title: "Installation - Docker image"
---

# Use Docker image

`php-parallel-processses` provide Docker images with everything installed: PHP, the library and Docker.

You can choose between this 3 versions:

* `alpine`: smallest version, but could be "too much simple" sometimes
* `buster`: middle version, contains almost everything needed
* `bookworm`: larger version, should contain what you need

# Basic usage

```bash
docker run --rm -it -v $(pwd):/app steevanb/php-parallel-processes:1.1.0-alpine php /app/parallel-processes.php 
```

# Docker outside of Docker (DooD)

Since `1.1.0`, Docker images contains docker, compose plugin and buildx plugin (only for `alpine` and `bookworm`).

You have to add a volume to your Docker socket, that's all:

```bash
docker run --rm -it -v $(pwd):/app -v /var/run/docker.sock:/var/run/docker.sock steevanb/php-parallel-processes:1.1.0-alpine php /app/parallel-processes.php 
```

Use `docker context inspect` to get your socket path if it's not `/var/run/docker.sock`.
In this case, keep `/var/run/docker.sock` for the volume target.

# Available versions

See [Docker tags](https://hub.docker.com/r/steevanb/php-parallel-processes/tags).

See [changelog](https://github.com/steevanb/php-parallel-processes/blob/master/changelog.md).
