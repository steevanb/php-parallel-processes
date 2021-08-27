FROM ubuntu:20.04

# For GitHub actions who need to do composer install
COPY --from=composer:2.1.6 /usr/bin/composer /usr/local/bin/composer

RUN \
    apt-get update \
    && apt-get install -y software-properties-common \
    && LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php \
    && apt-get install -y \
        bash \
        php7.4-cli \
        php7.4-dom \
        php7.4-mbstring \
        php8.0-cli \
        php8.0-dom \
        php8.0-mbstring \
    && apt-get purge -y software-properties-common \
    && apt-get clean \
    && apt-get autoclean
