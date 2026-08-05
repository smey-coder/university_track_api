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
# Stage 2: Runtime Environment
# ==============================================================================
FROM php:8.4-cli-alpine

ENV PORT=10000

# ដំឡើង dependencies និង PHP extensions ដែលចាំបាច់
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

# ចម្លង Vendor និង Source Code
COPY --from=vendor /app/vendor /app/vendor
COPY . /app

# Optimize Composer Autoloader
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer dump-autoload --optimize --no-dev && rm /usr/bin/composer

# បង្កើត Link Storage និងកំណត់ សិទ្ធិ (Permissions)
RUN rm -rf /app/public/storage \
    && php artisan storage:link \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache /app/public \
    && chmod -R 777 /app/storage /app/bootstrap/cache /app/public

EXPOSE 10000

# លុប Entrypoint ចាស់ដែលបង្កកំហុស 126
ENTRYPOINT []

# ដំណើរការ Laravel App តាមរយៈ PHP Artisan Serve
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=10000"]