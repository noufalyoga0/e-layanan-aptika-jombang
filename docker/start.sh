#!/bin/sh
set -e

cd /var/www/html

if [ -z "$APP_KEY" ]; then
  echo "ERROR: Set environment variable APP_KEY before deploy."
  echo "Generate locally: php artisan key:generate --show"
  exit 1
fi

php artisan config:clear
php artisan view:clear
php artisan route:clear
php artisan cache:clear
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link 2>/dev/null || true
php artisan config:cache
php artisan route:cache

echo "Server starting on port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
