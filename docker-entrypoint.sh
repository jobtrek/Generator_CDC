#!/bin/sh
set -e

echo "Starting application..."

echo "Running migrations..."
php artisan migrate --force

echo "Optimizing cache..."
php artisan optimize

# Restart worker every 1000 requests to prevent memory leaks
php artisan octane:start --server=frankenphp --host=0.0.0.0 --port=8000 --max-requests=1000
