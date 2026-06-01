# Stage 1: Build assets with Node.js
FROM node:20-alpine AS assets
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: PHP Application (Optimized for Laravel Octane)
FROM php:8.4-cli

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    libpq-dev \
    libwebp-dev \
    libbrotli-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip intl sockets opcache \
    && pecl install redis \
    && docker-php-ext-enable redis opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Copy built assets from the assets stage
COPY --from=assets /app/public/build ./public/build

# Copy optimized PHP configuration
COPY php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Install PHP dependencies
RUN composer update --no-dev --optimize-autoloader --no-interaction --no-progress

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose Octane port
EXPOSE 8000

# Entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["sh", "-c", "php artisan octane:start --server=roadrunner --host=0.0.0.0 --port=8000 --workers=${OCTANE_WORKERS:-8} --max-requests=${OCTANE_MAX_REQUESTS:-1000}"]
