# --- Étape 1 : On fabrique les assets (remplace npm run dev) ---
FROM node:20-alpine as build
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    postgresql-dev \
    libzip-dev \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    icu-dev

RUN docker-php-ext-install pdo_pgsql zip bcmath gd intl opcache

WORKDIR /var/www

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

COPY --from=build /app/public/build /var/www/public/build

RUN composer install --no-dev --optimize-autoloader

COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]
