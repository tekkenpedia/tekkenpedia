FROM php:8.4.13-zts-alpine3.22 AS php

COPY --from=composer:2.8.12 /usr/bin/composer /usr/local/bin/

RUN apk add --no-cache tidyhtml bash

ENV COMPOSER_CACHE_DIR=/app/var/composer

WORKDIR /app

FROM php AS dev

RUN \
    apk add --no-cache ffmpeg \
    # Purge
    && rm -rf \
        /var/cache/apk/* \
        /tmp/*

ENV I_AM_DOCKER_CONTAINER=true

FROM php AS ci

ENV COMPOSER_HOME=/composer

COPY docker/php/ci/composer.json ${COMPOSER_HOME}/composer.json

RUN \
    apk add --no-cache \
        shellcheck \
        # For zip
        zip libzip-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pcntl bz2 \
    && composer global update \
    && ln -s ${COMPOSER_HOME}/vendor/bin/composer-require-checker /usr/local/bin/composer-require-checker \
    && ln -s ${COMPOSER_HOME}/vendor/bin/phpcs /usr/local/bin/phpcs \
    && ln -s ${COMPOSER_HOME}/vendor/bin/unused_scanner /usr/local/bin/unused-scanner \
    && ln -s ${COMPOSER_HOME}/vendor/bin/phpstan /usr/local/bin/phpstan \
    # This tool is not compatible with Symfony 7
    && wget https://github.com/wapmorgan/PhpDeprecationDetector/releases/download/2.0.33/phpdd-2.0.33.phar -O /usr/local/bin/phpdd \
    && chmod +x /usr/local/bin/phpdd \
    # Purge
    && rm -rf \
        ${COMPOSER_HOME}/cache \
        ${COMPOSER_HOME}/composer.lock \
        /var/cache/apk/* \
        /tmp/*

COPY docker/php/ci/php.ini /usr/local/etc/php/conf.d/99-php.ini

ENV I_AM_DOCKER_CONTAINER=true
ENV COMPOSER_GLOBAL_AUTOLOAD_FILE_NAME=${COMPOSER_HOME}/vendor/autoload.php
