# syntax=docker/dockerfile:1

# -----------------------------------------------------------------------------
# Stage 1: Build frontend assets (Vite / Tailwind)
# The Laravel app lives in the web/ subfolder of this repo.
# -----------------------------------------------------------------------------
FROM node:22-alpine AS assets

WORKDIR /app

COPY web/package.json web/package-lock.json ./
RUN npm ci

COPY web/ ./
RUN npm run build

# -----------------------------------------------------------------------------
# Stage 2: Laravel runtime (PHP 8.4 + Apache)
# PHP >= 8.4.1 required by Symfony 8.1 (dependency of Laravel 13).
# -----------------------------------------------------------------------------
FROM php:8.4-apache AS runtime

ENV COMPOSER_ALLOW_SUPERUSER=1
ENV COMPOSER_NO_INTERACTION=1

WORKDIR /var/www/html/web

# System packages + required PHP extensions
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        curl \
        git \
        unzip \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        gd \
        mbstring \
        pdo_mysql \
        xml \
        zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Composer (official image)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Apache: point document root to Laravel's public/ and enable rewrite
RUN a2enmod rewrite headers
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# PHP runtime settings & opcache
COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/zz-opcache.ini

# Install Composer dependencies first (better layer caching)
COPY web/composer.json web/composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copy application source (.dockerignore keeps vendor/node_modules/.env out)
COPY web/ ./

# Build-time compiled assets override any stale local build
COPY --from=assets /app/public/build ./public/build

# Final dependency install + fix permissions
RUN composer install --no-dev --optimize-autoloader --prefer-dist \
    && chown -R www-data:www-data \
        /var/www/html/web/storage \
        /var/www/html/web/bootstrap/cache \
        /var/www/html/web/public

# Entrypoint: waits for DB, migrates, seeds once, caches config, starts Apache
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["start.sh"]