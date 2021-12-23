#!/usr/bin/env bash

set -eu

readonly CI_DOCKER_IMAGE_NAME="steevanb/php-parallel-processes:ci"
readonly RELEASE_DOCKER_IMAGE_NAME="steevanb/php-parallel-processes:release"
readonly PARALLEL_PROCESSES_DOCKER_IMAGE_NAME="steevanb/php-parallel-processes:parallel-processes-0.2.0"
