FROM node:24.13.0-trixie-slim as build
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
    bcmath \

ENV SERVER_NAME=:80
WORKDIR /app

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .
COPY --from=build /app/public/build /app/public/build

RUN composer install --no-dev --optimize-autoloader --no-interaction

RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=80", "--admin-port=2019"]
