#!/usr/bin/env sh

set -eu

if ! ${IS_IN_DOCKER}; then
    docker \
        run \
            --rm \
            --tty \
            ${DOCKER_INTERACTIVE_PARAMETER} \
            --volume "${ROOT_DIR}":/app \
            --user "$(id -u)":"$(id -g)" \
            --entrypoint bin/ci/"$(basename "${0}")" \
            --workdir /app \
            steevanb/php-parallel-process:ci \
            "${@}"
    exit 0
fi

"${PHP_BIN}" \
    vendor/bin/phpunit \
        --bootstrap "${COMPOSER_HOME_SYMFONY}"/vendor/autoload.php \
        --configuration config/ci/phpunit.xml \
        "${@}"
