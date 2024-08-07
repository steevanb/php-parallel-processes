ARG COMPOSER_VERSION

FROM composer:${COMPOSER_VERSION} AS composer

FROM php:8.2.22-cli-alpine3.20

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_HOME=/composer/common
ENV COMPOSER_HOME_SYMFONY_6_2=/composer/symfony-6-2

COPY docker/release/global.composer.json ${COMPOSER_HOME}/composer.json

RUN \
    apk add --no-cache \
        bash \
        cloc \
        jq \
        git \

    && docker-php-ext-install pcntl \

    # Install xdebug
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS linux-headers \
    && pecl install xdebug-3.2.0 \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps \

    # Install symfony components for coverage
    && COMPOSER_HOME=${COMPOSER_HOME_SYMFONY_6_2} \
        composer global require symfony/console:6.2.* symfony/process:6.2.* \

    # Purge
    && rm -rf /tmp/*

WORKDIR /app
