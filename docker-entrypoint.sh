#!/bin/sh
set -e

echo "Starting application..."

echo "🔄 Running migrations..."
php artisan migrate --force

echo "Optimizing cache..."
php artisan optimize

php artisan octane:start --server=frankenphp
