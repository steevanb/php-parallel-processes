#!/usr/bin/env bash

set -eu

if [ -n "${HOST_UID:-}" ]; then
    readonly DOCKER_UID="${HOST_UID}"
else
    readonly DOCKER_UID="$(id -u)"
fi

if [ -n "${HOST_GID:-}" ]; then
    readonly DOCKER_GID="${HOST_GID}"
else
    readonly DOCKER_GID="$(id -g)"
fi
