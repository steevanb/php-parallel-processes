#!/usr/bin/env bash

set -eu

source "${ROOT_DIR}"/config/docker/release-docker-image-name.env

BIN_DIR=bin/release \
    DOCKER_IMAGE_NAME="${RELEASE_DOCKER_IMAGE_NAME}" \
        source "${ROOT_DIR}"/bin/dockerise.inc.bash
