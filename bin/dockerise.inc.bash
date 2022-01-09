#!/usr/bin/env bash

set -eu

if type docker > /dev/null 2>&1; then
    readonly isInDocker=false
else
    readonly isInDocker=true
fi

if ! ${isInDocker}; then
    source "${ROOT_DIR}"/bin/docker-interactive-parameter.inc.bash

    docker \
        run \
            --rm \
            --tty \
            ${DOCKER_INTERACTIVE_PARAMETER} \
            --volume "${ROOT_DIR}":/app \
            --user "$(id -u)":"$(id -g)" \
            --entrypoint "${BIN_DIR}"/"$(basename "${0}")" \
            --workdir /app \
            "${DOCKER_IMAGE_NAME}" \
            "${@}"
    exit 0
fi
