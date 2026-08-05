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

# Copy vendor dependencies from builder stage
COPY --from=vendor /app/vendor /app/vendor

# Copy application source code
COPY . /app

# Optimize Composer Autoloader
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer dump-autoload --optimize --no-dev && rm /usr/bin/composer

# Create public storage symlink
RUN php artisan storage:link || true

# Grant full read/write/execute permissions to application folders
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache /app/public \
    && chmod -R 777 /app/storage /app/bootstrap/cache /app/public

# Explicitly grant executable permissions to the frankenphp binary
RUN chmod +x /usr/local/bin/frankenphp

EXPOSE 10000

# Override the default entrypoint to prevent docker-php-entrypoint permission block
ENTRYPOINT []

# Run FrankenPHP server
CMD ["frankenphp php-server --root /app/public --listen :10000"]