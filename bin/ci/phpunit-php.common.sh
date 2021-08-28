#!/usr/bin/env sh

set -eu

if type docker > /dev/null 2>&1; then
    docker \
        run \
            -it \
            --tty \
            --rm \
            --volume "${ROOT_DIR}":/app \
            --user "$(id -u)":"$(id -g)" \
            --entrypoint bin/ci/"$(basename "${0}")" \
            --workdir /app \
            steevanb/php-parallel-process:ci
else
    "${PHP_BIN}" \
        vendor/bin/phpunit \
            --bootstrap "${COMPOSER_HOME_SYMFONY}"/vendor/autoload.php \
            --configuration config/ci/phpunit.xml
fi
