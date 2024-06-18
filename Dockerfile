FROM php:7.4 as php

RUN apt-get update -y
RUN apt-get install -y unzip libpq-dev libcurl4-gnutls-dev
RUN docker-php-ext-install pdo pdo_mysql bcmath

WORKDIR /var/www
COPY . .

RUN apt-get update \
    && apt-get install -y \
        git \
        curl \
        zip \
        unzip \
        libonig-dev \
        libzip-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-install pdo_mysql \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \

RUN composer install --no-plugins --no-scripts --no-autoloader

ENTRYPOINT ["Docker/entrypoint.sh"]
