#!/bin/sh
set -e

echo " Logs..."

echo " Migrations..."
php artisan migrate --force

echo " Cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo " Storage Link..."
rm -rf public/storage
php artisan storage:link

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

echo "Prêt Lancement de PHP."
exec "$@"
