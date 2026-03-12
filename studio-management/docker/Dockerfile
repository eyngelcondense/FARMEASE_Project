# Multi-stage PHP Apache Dockerfile for FARMEASE Studio (copied from staff-management)
FROM php:8.2-apache
WORKDIR /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN apt-get update && apt-get install -y libzip-dev zlib1g-dev git libicu-dev libpng-dev libjpeg-dev libfreetype-dev --no-install-recommends && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install mysqli pdo pdo_mysql intl gd zip
RUN a2enmod rewrite
COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction && composer dump-autoload
COPY app ./app/
COPY public ./public/
COPY writable ./writable/
COPY spark ./
COPY .env ./
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html && chmod -R 775 /var/www/html/writable
EXPOSE 80
