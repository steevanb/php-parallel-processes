ARG COMPOSER_VERSION

FROM composer:${COMPOSER_VERSION} AS composer

FROM php:8.2.29-cli-alpine3.21

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_HOME=/composer/common
ENV COMPOSER_HOME_SYMFONY_7_0=/composer/symfony-7-0

COPY docker/release/global.composer.json ${COMPOSER_HOME}/composer.json

RUN \
    apk add --no-cache \
        bash \
        cloc \
        jq \
        git \

    && docker-php-ext-install pcntl \

    # Install xdebug
    && apk add --no-cache --virtual .build-deps ${PHPIZE_DEPS} linux-headers \
    && pecl install xdebug-3.2.0 \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps \

    # Install symfony components for coverage
    && COMPOSER_HOME=${COMPOSER_HOME_SYMFONY_7_0} \
        composer global require symfony/console:7.0.* symfony/process:7.0.* \

    # Purge
    && rm -rf /tmp/*

WORKDIR /app
