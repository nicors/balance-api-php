FROM php:8.1.1-fpm

RUN apt update && apt install -y git

copy --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www