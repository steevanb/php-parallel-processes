#!/usr/bin/env bash

set -eu

function buildDockerImage() {
    local dockerImageName="${1}"
    local dockerFilePath="${2}"
    local dockerBuildParams="${3}"

    if [ "${refresh}" == true ]; then
        local refreshArguments="--no-cache --pull"
    else
        local refreshArguments=
    fi

    docker \
        build \
            --file "${dockerFilePath}" \
            --tag "${dockerImageName}" \
            --build-arg DOCKER_UID="$(id -u)" \
            --build-arg DOCKER_GID="$(id -g)" \
            --build-arg COMPOSER_VERSION="${COMPOSER_VERSION}" \
            ${refreshArguments} \
            ${dockerBuildParams} \
            "${ROOT_DIR}"
}

function pushDockerImage() {
    local dockerImageName="${1}"

    echo "Push Docker image ${dockerImageName}."
    docker push "${dockerImageName}"
}

refresh=false
push=false
for param in "${@}"; do
    if [ "${param}" == "--refresh" ]; then
        refresh=true
    elif [ "${param}" == "--push" ]; then
        push=true
    fi
done

buildDockerImage "${DOCKER_IMAGE_NAME}" "${DOCKER_FILE_PATH}" "${DOCKER_BUILD_PARAMS:-}"

if [ "${push}" == true ]; then
    pushDockerImage "${DOCKER_IMAGE_NAME}"
fi
