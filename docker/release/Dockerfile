FROM php:8.0.10-cli-alpine3.14

COPY --from=composer:2.1.6 /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_HOME=/composer/common
ENV COMPOSER_HOME_SYMFONY_5_3=/composer/symfony-5-3

RUN \
    apk add --no-cache \
        bash \
        cloc \
        jq \
        git \

    # Install xdebug \
    && apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install xdebug-3.0.4 \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps \

    # Install infection
    && composer global require infection/infection:0.24.* \
    && ln -s ${COMPOSER_HOME}/vendor/bin/infection /usr/local/bin/infection \

    # Install symfony components for coverage
    && COMPOSER_HOME=${COMPOSER_HOME_SYMFONY_5_3} \
        composer global require symfony/console:5.3.* symfony/process:5.3.* \

    # Add symlink to make bin/composer works, do it better one day ;) \
    && ln -s /usr/local/bin/php /usr/bin/php7.4 \

    # Purge
    && rm -rf /tmp/*

WORKDIR /app
