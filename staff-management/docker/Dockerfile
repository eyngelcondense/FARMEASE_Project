# Use official PHP with Apache image (multi-arch)
FROM php:8.2-apache

# Set working directory to /var/www/html
WORKDIR /var/www/html

# --- 1. INSTALL COMPOSER EXECUTABLE ---
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# --- 2. INSTALL PHP EXTENSIONS AND APACHE MODULES ---
# Update package lists and install dependencies
# FIX: Added libpng-dev, libjpeg-dev, and libfreetype-dev for successful GD extension install.
RUN apt-get update && apt-get install -y libzip-dev zlib1g-dev git libicu-dev \
    libpng-dev libjpeg-dev libfreetype-dev \
    --no-install-recommends \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions for MySQL/DB interaction and required extensions (intl, gd)
RUN docker-php-ext-install mysqli pdo pdo_mysql intl gd

# Enable Apache mod_rewrite for CodeIgniter routing
RUN a2enmod rewrite

# --- 3. COPY DEPENDENCY FILES & INSTALL VENDOR ---
# Copy composer files first to leverage Docker layer caching
COPY composer.json composer.lock ./

# Install PHP dependencies. This creates the 'vendor' directory.
# FIX: Added 'composer dump-autoload' to ensure the autoloader cache is correct.
RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction && composer clear-cache && composer dump-autoload

# --- 4. COPY REMAINING PROJECT FILES ---
# FIX: Explicitly copy directories and files to ensure 'writable' is created.
COPY app ./app/
COPY public ./public/
COPY writable ./writable/
COPY spark ./.
COPY .env ./.

# Set timezone
ENV TZ=Asia/Manila

# --- 5. FINALIZE CONFIGURATION & PERMISSIONS ---
# Fix permissions (This is now guaranteed to work as 'writable' should exist)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html \
    && chown -R www-data:www-data /var/www/html/writable \
    && chmod -R 775 /var/www/html/writable

RUN apt-get update && \
apt-get install -y zip unzip && \
docker-php-ext-install zip

RUN composer require dompdf/dompdf


# Copy Apache config to the correct location
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Expose Apache port
EXPOSE 80
