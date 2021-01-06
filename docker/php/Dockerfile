FROM php:7.4.6-fpm as php

WORKDIR /app

ARG APP_ENV=prod
ARG CONFIGURATION_FILE="php.${APP_ENV}.ini"

RUN apt -q update && apt -q install -y libicu-dev libonig-dev libzip-dev zip && \
    docker-php-ext-install -j$(nproc) zip intl opcache pdo pdo_mysql mbstring

COPY $CONFIGURATION_FILE /usr/local/etc/php/php.ini
COPY fpm.conf /usr/local/etc/php-fpm.d/zzz.conf

COPY --chown=www-data:www-data . .
