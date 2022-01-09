#!/usr/bin/env bash

# Some terminals are not interactives, like GitLab CI for example

set -eu

set +e
tty -s > /dev/null 2>&1 && isInteractiveShell=true || isInteractiveShell=false
set -e
if ${isInteractiveShell}; then
    readonly DOCKER_INTERACTIVE_PARAMETER="--interactive"
else
    readonly DOCKER_INTERACTIVE_PARAMETER=
fi
