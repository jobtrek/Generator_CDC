#!/bin/sh
set -e

echo " Starting application..."

echo "Running migrations..."
php artisan migrate --force

php artisan octane:install --server=frankenphp --no-interaction || true

echo "⚡ Optimizing cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🔗 Linking storage..."
rm -rf public/storage
php artisan storage:link

chown -R www-data:www-data /app/storage /app/bootstrap/cache

echo "✅ Ready to serve!"
exec "$@"
