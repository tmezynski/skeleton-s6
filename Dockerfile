FROM php:8.3-fpm-alpine AS base

RUN set -eux
RUN apk update --no-cache

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN install-php-extensions pcov redis pdo pdo_pgsql rdkafka \
    && rm -rf /tmp/*

WORKDIR /app

FROM base AS development

RUN install-php-extensions xdebug \
    && rm -rf /tmp/*

COPY build/php/dev.ini $PHP_INI_DIR/conf.d/php-ini-overrides.ini
