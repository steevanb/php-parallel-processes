# Do not use Alpine because docker-compose will not works
FROM php:8.1.1-cli-buster

COPY --from=composer:2.2.3 /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_HOME=/composer
ENV COMPOSER_GLOBAL_AUTOLOAD_FILE_NAME=/composer/vendor/autoload.php

ARG REPOSITORY_VERSION

RUN \
    apt-get update \
    && apt-get install -y bash unzip \
    && composer global require steevanb/php-parallel-processes:${REPOSITORY_VERSION} \
    && apt-get autoremove \
    && apt-get clean
