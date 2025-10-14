ARG COMPOSER_VERSION

FROM composer:${COMPOSER_VERSION} AS composer

FROM ubuntu:24.04

COPY --from=composer /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_HOME=/composer/common
ENV COMPOSER_HOME_SYMFONY_7_0=/composer/symfony-7-0
ENV COMPOSER_HOME_SYMFONY_7_1=/composer/symfony-7-1
ENV COMPOSER_HOME_SYMFONY_7_2=/composer/symfony-7-2
ENV COMPOSER_HOME_SYMFONY_7_3=/composer/symfony-7-3

ENV PHPSTAN_BIN_PATH=/usr/local/bin/phpstan

COPY docker/ci/composer.json ${COMPOSER_HOME}/composer.json

RUN \
    apt-get update \
    # To add add-apt-repository
    && apt-get install -y software-properties-common \
    && LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php \
    && apt-get install -y \
        bash \
        shellcheck \
        # PHP 8.2
        php8.2-cli \
        php8.2-dom \
        php8.2-mbstring \
        # PHP 8.3
        php8.3-cli \
        php8.3-dom \
        php8.3-mbstring \
        # PHP 8.4
        php8.4-cli \
        php8.4-dom \
        php8.4-mbstring \
        # For Composer
        curl \
        zip \
        php8.2-curl \
        php8.2-zip \
        # For coverage
        php8.2-xdebug \
    && update-alternatives --set php /usr/bin/php8.2 \

    # Install CI tools
    && cd ${COMPOSER_HOME} \
    && composer update \
    && ln -s ${COMPOSER_HOME}/vendor/bin/composer-require-checker /usr/local/bin/composer-require-checker \
    && ln -s ${COMPOSER_HOME}/vendor/bin/phpdd /usr/local/bin/phpdd \
    && ln -s ${COMPOSER_HOME}/vendor/bin/phpcs /usr/local/bin/phpcs \
    && ln -s ${COMPOSER_HOME}/vendor/bin/phpstan ${PHPSTAN_BIN_PATH} \
    && ln -s ${COMPOSER_HOME}/vendor/bin/unused_scanner /usr/local/bin/unused-scanner \

    # Install Symfony components
    && COMPOSER_HOME=${COMPOSER_HOME_SYMFONY_7_0} composer global require symfony/process:7.0.* symfony/console:7.0.* \
    && COMPOSER_HOME=${COMPOSER_HOME_SYMFONY_7_1} composer global require symfony/process:~7.1.5 symfony/console:7.1.* \
    && COMPOSER_HOME=${COMPOSER_HOME_SYMFONY_7_2} composer global require symfony/process:7.2.* symfony/console:7.2.* \
    && COMPOSER_HOME=${COMPOSER_HOME_SYMFONY_7_3} composer global require symfony/process:7.3.* symfony/console:7.3.* \

    # Purge
    && apt-get purge -y software-properties-common \
    && apt-get autoremove -y \
    && apt-get clean \
    && apt-get autoclean \
    && rm -rf \
        ${COMPOSER_HOME}/.htaccess \
        ${COMPOSER_HOME}/cache \
        ${COMPOSER_HOME}/composer.lock \
    && rm -rf \
        ${COMPOSER_HOME_SYMFONY_7_0}/.htaccess \
        ${COMPOSER_HOME_SYMFONY_7_0}/cache \
        ${COMPOSER_HOME_SYMFONY_7_0}/composer.json \
        ${COMPOSER_HOME_SYMFONY_7_0}/composer.lock \
    && rm -rf \
        ${COMPOSER_HOME_SYMFONY_7_1}/.htaccess \
        ${COMPOSER_HOME_SYMFONY_7_1}/cache \
        ${COMPOSER_HOME_SYMFONY_7_1}/composer.json \
        ${COMPOSER_HOME_SYMFONY_7_1}/composer.lock \
    && rm -rf \
        ${COMPOSER_HOME_SYMFONY_7_2}/.htaccess \
        ${COMPOSER_HOME_SYMFONY_7_2}/cache \
        ${COMPOSER_HOME_SYMFONY_7_2}/composer.json \
        ${COMPOSER_HOME_SYMFONY_7_2}/composer.lock \
    && rm -rf \
        ${COMPOSER_HOME_SYMFONY_7_3}/.htaccess \
        ${COMPOSER_HOME_SYMFONY_7_3}/cache \
        ${COMPOSER_HOME_SYMFONY_7_3}/composer.json \
        ${COMPOSER_HOME_SYMFONY_7_3}/composer.lock \
    && rm -rf /tmp/*
