# ==============================================================================
# Stage 1: Vendor Dependencies
# ==============================================================================
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --ignore-platform-reqs \
    --no-scripts

# ==============================================================================
# Stage 2: Runtime Environment
# ==============================================================================
FROM dunglas/frankenphp:1-php8.4-alpine

# Set environment variables
ENV SERVER_NAME=":10000"
ENV PORT=10000
ENV FRANKENPHP_CONFIG="web_root /app/public"

# Install required system dependencies & PHP extensions
RUN apk add --no-cache \
        icu-dev \
        libzip-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        zip \
        intl \
        bcmath \
        opcache

WORKDIR /app

# Copy dependencies from builder
COPY --from=vendor /app/vendor /app/vendor

# Copy application source code
COPY . /app

# Optimize Composer Autoloader
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer dump-autoload --optimize --no-dev && rm /usr/bin/composer

# Set correct permissions for Laravel runtime directories
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Create storage symlink AND ensure public/storage folder permissions are writable
RUN php artisan storage:link || true \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache /app/public
    
USER www-data

EXPOSE 10000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]