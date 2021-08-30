FROM php:8.0.10-cli-alpine3.14

COPY --from=composer:2.1.6 /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_HOME_SYMFONY_5_3=/composer/symfony-5-3

RUN \
    apk add --no-cache \
        bash \
        cloc \
        jq \
        git \

    # Install Symfony components
    && COMPOSER_HOME=${COMPOSER_HOME_SYMFONY_5_3} \
        composer global require symfony/console:5.3.* symfony/process:5.3.*
RUN \
    # Purge
    rm -rf /tmp/*
