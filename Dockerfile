FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction

COPY . .

RUN cp .env.example .env

RUN php artisan key:generate

RUN php artisan package:discover --ansi

RUN composer dump-autoload --optimize

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=${PORT:-10000}