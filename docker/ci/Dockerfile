FROM ubuntu:20.04

COPY --from=composer:2.1.6 /usr/bin/composer /usr/local/bin/composer

ENV COMPOSER_HOME=/composer
ENV COMPOSER_HOME_SYMFONY_5_0="/composer/symfony-5-0"
ENV COMPOSER_HOME_SYMFONY_5_1="/composer/symfony-5-1"
ENV COMPOSER_HOME_SYMFONY_5_2="/composer/symfony-5-2"
ENV COMPOSER_HOME_SYMFONY_5_3="/composer/symfony-5-3"

RUN \
    apt-get update \
    # To add add-apt-repository
    && apt-get install -y software-properties-common \
    && LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php \
    && apt-get install -y \
        bash \
        shellcheck \
        php7.4-cli \
        php7.4-dom \
        php7.4-mbstring \
        php8.0-cli \
        php8.0-dom \
        php8.0-mbstring \
        # For Composer
        curl zip php8.0-curl php8.0-zip \
        # For coverage \
        php8.0-xdebug \
        # For bin/ci/phpunit-coverage-badge \
        wget \
    && composer global require \
        phpstan/phpstan:0.12.* \
        phpstan/phpstan-deprecation-rules:0.12.* \
        phpstan/phpstan-phpunit:0.12.* \
        phpstan/phpstan-strict-rules:0.12.* \
        phpstan/phpstan-symfony:0.12.* \
        maglnet/composer-require-checker:3.3.* \
        wapmorgan/php-deprecation-detector:2.0.* \
        steevanb/php-code-sniffs:4.2.* \
    && ln -s ${COMPOSER_HOME}/vendor/bin/composer-require-checker /usr/local/bin/composer-require-checker \
    && ln -s ${COMPOSER_HOME}/vendor/bin/phpdd /usr/local/bin/phpdd \
    && ln -s ${COMPOSER_HOME}/vendor/bin/phpcs /usr/local/bin/phpcs \
    && ln -s ${COMPOSER_HOME}/vendor/bin/phpstan /usr/local/bin/phpstan \
    && COMPOSER_HOME=/composer/symfony-5-0 \
        composer global require symfony/console:5.2.* symfony/process:5.0.* \
    && COMPOSER_HOME=/composer/symfony-5-1 \
        composer global require symfony/console:5.2.* symfony/process:5.1.* \
    && COMPOSER_HOME=/composer/symfony-5-2 \
        composer global require symfony/console:5.2.* symfony/process:5.2.* \
    && COMPOSER_HOME=/composer/symfony-5-3 \
        composer global require symfony/console:5.3.* symfony/process:5.3.* \
    && apt-get purge -y software-properties-common \
    && apt-get autoremove -y \
    && apt-get clean \
    && apt-get autoclean \
    && rm -rf /tmp/*
