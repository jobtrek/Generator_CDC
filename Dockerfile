FROM node:25.5.0-trixie-slim AS build
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

COPY ./composer.json ./composer.lock ./
RUN composer install --no-cache --prefer-dist --no-autoloader --no-scripts --no-progress

COPY --chown=www-data:www-data . .
COPY --from=build --chown=www-data:www-data /app/public/build /app/public/build

RUN mkdir -p storage/framework/{sessions,views,cache,testing} storage/logs bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache

RUN composer dump-autoload --classmap-authoritative
RUN php artisan storage:link

COPY --chmod=755 docker-entrypoint.sh /usr/local/bin/

USER www-data

ENTRYPOINT ["docker-entrypoint.sh"]
