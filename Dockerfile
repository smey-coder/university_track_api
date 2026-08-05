# ==============================================================================
# Stage 1: Build Dependencies
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
# Stage 2: Runtime Application
# ==============================================================================
FROM dunglas/frankenphp:1-php8.4-alpine

ENV SERVER_NAME=":10000"
ENV PORT=10000
ENV FRANKENPHP_CONFIG="web_root /app/public"

# Install system libraries & PHP extensions
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

# Copy Composer dependencies
COPY --from=vendor /app/vendor /app/vendor

# Copy application source code
COPY . /app

# Optimize Composer Autoloader
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer dump-autoload --optimize --no-dev && rm /usr/bin/composer

# Ensure permissions and link storage safely
RUN rm -rf /app/public/storage \
    && php artisan storage:link \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache /app/public \
    && chmod -R 777 /app/storage /app/bootstrap/cache /app/public

EXPOSE 10000

# Simple, reliable startup command
CMD frankenphp php-server --root /app/public --listen :10000