FROM php:8.3.7-zts-alpine3.20 as php

COPY --from=composer:2.7.6 /usr/bin/composer /usr/local/bin/

RUN apk add --no-cache tidyhtml bash

ENV COMPOSER_CACHE_DIR=/app/var/composer

WORKDIR /app

FROM php as dev

RUN \
    apk add --no-cache ffmpeg \

    # Purge
    && rm -rf \
        /var/cache/apk/* \
        /tmp/*

ENV I_AM_DOCKER_CONTAINER=true

FROM php as ci

ENV COMPOSER_HOME=/composer

COPY docker/php/ci/composer.json ${COMPOSER_HOME}/composer.json

RUN \
    apk add --no-cache \
        shellcheck \
        # For zip \
        zip libzip-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pcntl \
    && cd ${COMPOSER_HOME} \
    && composer update \
    && ln -s ${COMPOSER_HOME}/vendor/bin/composer-require-checker /usr/local/bin/composer-require-checker \
    && ln -s ${COMPOSER_HOME}/vendor/bin/phpcs /usr/local/bin/phpcs \
    && ln -s ${COMPOSER_HOME}/vendor/bin/unused_scanner /usr/local/bin/unused-scanner \
    && ln -s ${COMPOSER_HOME}/vendor/bin/phpstan /usr/local/bin/phpstan \
    && ln -s ${COMPOSER_HOME}/vendor/bin/phpdd /usr/local/bin/phpdd \

    # Purge
    && rm -rf \
        ${COMPOSER_HOME}/cache \
        ${COMPOSER_HOME}/composer.lock \
        /var/cache/apk/* \
        /tmp/*

ENV I_AM_DOCKER_CONTAINER=true
ENV COMPOSER_GLOBAL_AUTOLOAD_FILE_NAME=${COMPOSER_HOME}/vendor/autoload.php
