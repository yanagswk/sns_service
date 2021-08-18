# FROM php:8.0-fpm-alpine
# FROM php:7.4.1-fpm-alpine
# FROM php:8.0-fpm-buster
FROM php:7.4.1-fpm

# CORY php.ini
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

# Composer install
COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

# install Node.js
# COPY --from=node:10.22 /usr/local/bin /usr/local/bin
# COPY --from=node:10.22 /usr/local/lib /usr/local/lib
RUN apt-get update \
    && apt-get install -y wget git zip vim unzip libpq-dev libzip-dev libicu-dev libonig-dev \
    && docker-php-ext-install pdo_mysql bcmath \
    && : 'Install Node.js' \
    &&  curl -sL https://deb.nodesource.com/setup_14.x | bash - \
    && apt-get install -y nodejs 

WORKDIR /var/www/html
