FROM php:8.3.3-zts-alpine3.19

COPY --from=composer:2.7.1 /usr/bin/composer /usr/local/bin/

RUN apk add ffmpeg tidyhtml

ENV COMPOSER_CACHE_DIR=/app/var/composer

WORKDIR /app
