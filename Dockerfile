FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    git \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql opcache dom ctype

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

EXPOSE 9000

CMD ["php-fpm"]