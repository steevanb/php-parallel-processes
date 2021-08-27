FROM php:8.0.10-cli-alpine3.14

# For GitHub actions who need to do composer install
COPY --from=composer:2.1.6 /usr/bin/composer /usr/local/bin/composer

RUN \
    apk add --no-cache bash \
    # xdebug dependencies
    && apk add --no-cache --virtual .build-deps autoconf make g++ \
    && pecl install xdebug-3.0.4 \
    && docker-php-ext-enable xdebug \
    && apk del .build-deps
