#syntax=docker/dockerfile:1.4
FROM php:8.3-fpm-alpine3.17

RUN set -eux && \
    apk update && \
    apk add git && \
    apk add --no-cache patch && \
    apk add --no-cache nodejs npm && \
    apk del && \
    rm -rf /var/lib/apt/lists/* && \
    mkdir /root/history

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions @composer pgsql pdo_pgsql intl zip && \
    ln -s composer /usr/local/bin/c

COPY --link php.ini $PHP_INI_DIR/conf.d/
COPY --link fpm.conf /usr/local/etc/php-fpm.d/zzz-docker.conf
RUN set -eux && \
    cp "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini" && \
    mkdir /var/run/php
