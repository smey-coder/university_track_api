# # ==============================================================================
# # Stage 1: Vendor Dependencies
# # ==============================================================================
# FROM composer:2 AS vendor

# WORKDIR /app

# COPY composer.json composer.lock ./

# RUN composer install \
#     --no-dev \
#     --no-interaction \
#     --prefer-dist \
#     --ignore-platform-reqs \
#     --no-scripts

# # ==============================================================================
# # Stage 2: Runtime Environment
# # ==============================================================================
# FROM dunglas/frankenphp:1-php8.4-alpine

# # Set environment variables
# ENV SERVER_NAME=":10000"
# ENV PORT=10000

# # Install required system dependencies & PHP extensions
# RUN apk add --no-cache \
#         icu-dev \
#         libzip-dev \
#     && docker-php-ext-configure intl \
#     && docker-php-ext-install -j$(nproc) \
#         pdo_mysql \
#         zip \
#         intl \
#         bcmath \
#         opcache

# WORKDIR /app

# # Copy dependencies from builder
# COPY --from=vendor /app/vendor /app/vendor

# # Copy application source code
# COPY . /app

# # Optimize Composer Autoloader
# COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
# RUN composer dump-autoload --optimize --no-dev && rm /usr/bin/composer

# # Set correct permissions for Laravel runtime directories
# RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# USER www-data

# EXPOSE 10000

# CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]

# ==============================================================================
# Stage 1: Install Composer Dependencies
# ==============================================================================
FROM composer:2 AS vendor

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --ignore-platform-reqs \
    --no-scripts

# ==============================================================================
# Stage 2: Runtime
# ==============================================================================
FROM dunglas/frankenphp:1-php8.4-alpine

WORKDIR /app

ENV SERVER_NAME=":10000"
ENV PORT=10000

# Install PHP Extensions
RUN apk add --no-cache \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
        pdo_mysql \
        bcmath \
        zip \
        intl \
        opcache

# Copy vendor
COPY --from=vendor /app/vendor /app/vendor

# Copy application
COPY . .

# Copy Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Optimize Laravel
RUN composer dump-autoload --optimize --no-dev

# Create storage symlink
RUN php artisan storage:link || true

# Cache configuration
RUN php artisan config:clear
RUN php artisan cache:clear
RUN php artisan route:clear
RUN php artisan view:clear
RUN php artisan config:cache

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

USER www-data

EXPOSE 10000

CMD ["php","artisan","serve","--host=0.0.0.0","--port=10000"]