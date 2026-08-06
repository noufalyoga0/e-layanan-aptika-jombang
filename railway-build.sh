#!/bin/bash
# Railway build script — jangan generate APP_KEY di sini (pakai env var Railway)

set -e

echo "=== Installing PHP dependencies ==="
composer install --no-dev --optimize-autoloader

echo "=== Build complete (migrate runs on start) ==="
