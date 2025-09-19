# ---- Build stage: install PHP dependencies with Composer ----
FROM composer:2 AS vendor
WORKDIR /app

# Copy composer files and install dependencies first for better caching
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --prefer-dist

# Copy the rest of the application
COPY . .

# Optimize autoloader for production
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-progress \
    --optimize-autoloader


# ---- Runtime stage: Apache + PHP ----
FROM php:8.3-apache

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
        libicu-dev \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        unzip \
        git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        intl \
        mbstring \
        mysqli \
        pdo \
        pdo_mysql \
        gd \
        zip \
    && a2enmod rewrite \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Set the Apache DocumentRoot to /var/www/html/public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/sites-available/000-default.conf \
        /etc/apache2/sites-available/default-ssl.conf

# Copy application from build stage
WORKDIR /var/www/html
COPY --from=vendor /app .

# Ensure runtime directories exist and are writable
RUN mkdir -p writable/cache writable/logs writable/session writable/uploads writable/debugbar \
    && chown -R www-data:www-data /var/www/html \
    && find writable -type d -exec chmod 775 {} \; \
    && find writable -type f -exec chmod 664 {} \;

# Expose port 80 (Railway maps this automatically for Docker projects)
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
