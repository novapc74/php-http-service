FROM php:8.4-cli-alpine

RUN mv $PHP_INI_DIR/php.ini-development $PHP_INI_DIR/php.ini

COPY ./php/conf.d $PHP_INI_DIR/conf.d

RUN apk update && \
    apk add --no-cache \
        git \
        g++ \
        bash \
        make \
        unzip \
        shadow \
        libtool \
        icu-dev \
        autoconf \
        libpng-dev \
        libzip-dev \
        libwebp-dev \
        freetype-dev \
        libwebp-tools \
        libjpeg-turbo-dev \
    && docker-php-ext-install pdo pdo_mysql zip intl \
    && rm -rf /var/lib/apt/lists/*

ENV COMPOSER_ALLOW_SUPERUSER 1
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer --quiet

WORKDIR /project
ARG UID=1000
RUN useradd -d /home/develop -u $UID -m -s /bin/bash develop && \
    echo "develop:develop" && \
    chown -R develop:develop /project

USER develop
