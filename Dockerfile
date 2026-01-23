FROM node:24.13.0-trixie-slim AS build
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

FROM dunglas/frankenphp:1.11.1-php8.5.2-trixie

RUN install-php-extensions \
    pdo_pgsql \
    gd \
    intl \
    zip \
    opcache \
    pcntl \
    bcmath

# ENV SERVER_NAME=:80

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /app

COPY --from=composer:2.9.4 /usr/bin/composer /usr/bin/composer

COPY ./composer.* ./
RUN composer install --no-cache --prefer-dist --no-autoloader --no-scripts --no-progress

COPY . .
COPY --from=build /app/public/build /app/public/build

RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/framework/testing storage/logs bootstrap/cache

RUN chmod -R 775 storage bootstrap/cache

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

RUN composer dump-autoload --classmap-authoritative
RUN php artisan storage:link

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

USER www-data

ENTRYPOINT ["docker-entrypoint.sh"]
