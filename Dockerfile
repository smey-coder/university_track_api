FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo_mysql zip

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN php artisan config:cache

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=$PORT