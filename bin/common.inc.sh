#!/usr/bin/env bash

set -eu

if type docker > /dev/null 2>&1; then
    readonly IS_IN_DOCKER=false
else
    readonly IS_IN_DOCKER=true
fi

set +e
tty -s && IS_INTERACTIVE_SHELL=true || IS_INTERACTIVE_SHELL=false
set -e

if ${IS_INTERACTIVE_SHELL}; then
    DOCKER_INTERACTIVE_PARAMETER="--interactive"
else
    DOCKER_INTERACTIVE_PARAMETER=
fi
