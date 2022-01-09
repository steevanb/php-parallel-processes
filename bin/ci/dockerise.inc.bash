#!/usr/bin/env bash

set -eu

source "${ROOT_DIR}"/config/docker/ci-docker-image-name.env

BIN_DIR=bin/ci \
    DOCKER_IMAGE_NAME="${CI_DOCKER_IMAGE_NAME}" \
        source "${ROOT_DIR}"/bin/dockerise.inc.bash
