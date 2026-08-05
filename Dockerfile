# ==============================================================================
# Stage 1: Build Composer Dependencies
# ==============================================================================
FROM composer:2 AS vendor

WORKDIR /app

# Copy composer definition files first to leverage Docker layer caching
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --ignore-platform-reqs \
    --no-scripts

# ==============================================================================
# Stage 2: Production Runtime Environment
# ==============================================================================
FROM dunglas/frankenphp:1-php8.4-alpine

# Set default application environment variables
ENV SERVER_NAME=":10000"
ENV PORT=10000
ENV FRANKENPHP_CONFIG="web_root /app/public"

# Install required system dependencies and PHP extensions
RUN apk add --no-cache \
        icu-dev \
        libzip-dev \
        postgresql-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        pdo_pgsql \
        zip \
        intl \
        bcmath \
        opcache

WORKDIR /app

# Copy dependencies from Stage 1
COPY --from=vendor /app/vendor /app/vendor

# Copy application source code
COPY . /app

# Install composer temporarily to build optimized autoloader, then clean up
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer dump-autoload --optimize --no-dev && rm /usr/bin/composer

# Create storage symlink and assign ownership permissions to www-data user
RUN php artisan storage:link || true \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Switch to non-root user for security
USER www-data

EXPOSE 10000

# Execute runtime optimization commands on boot, then start FrankenPHP server
CMD ["sh", "-c", "php artisan config:cache && php artisan route:cache && php artisan view:cache && frankenphp php-server --root /app/public --listen :10000"]