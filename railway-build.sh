#!/bin/bash
# Railway Build Script for Laravel E-Layanan APTIKA Jombang

echo "=== Installing PHP dependencies ==="
composer install --no-dev --optimize-autoloader

echo "=== Generating App Key ==="
php artisan key:generate --force

echo "=== Running database migrations ==="
php artisan migrate --force

echo "=== Seeding database with demo data ==="
php artisan db:seed --force

echo "=== Caching config, routes, and views ==="
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=== Build complete! ==="
