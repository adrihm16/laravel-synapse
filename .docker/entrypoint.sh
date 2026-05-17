#!/bin/bash
set -e

# Copy .env from example if it doesn't exist
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Write Docker environment variables into .env so Laravel reads the correct values
sed -i "s|^DB_HOST=.*|DB_HOST=${DB_HOST}|" .env
sed -i "s|^DB_PORT=.*|DB_PORT=${DB_PORT}|" .env
sed -i "s|^DB_DATABASE=.*|DB_DATABASE=${DB_DATABASE}|" .env
sed -i "s|^DB_USERNAME=.*|DB_USERNAME=${DB_USERNAME}|" .env
sed -i "s|^DB_PASSWORD=.*|DB_PASSWORD=${DB_PASSWORD}|" .env
sed -i "s|^APP_URL=.*|APP_URL=${APP_URL}|" .env
sed -i "s|^APP_ENV=.*|APP_ENV=${APP_ENV}|" .env
sed -i "s|^APP_DEBUG=.*|APP_DEBUG=${APP_DEBUG}|" .env

# Recreate storage directories after volume mount (volume replaces Dockerfile-created dirs)
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Generate APP_KEY if not set
php artisan key:generate --force

# Register package manifests (deferred from build time)
php artisan package:discover --ansi

# Wait for MySQL to accept connections
echo "Waiting for database..."
until php artisan db:show --json > /dev/null 2>&1; do
    sleep 2
done
echo "Database is ready."

# Only the app service runs migrations and storage setup (not the queue worker)
if [[ "$*" != *"queue"* ]]; then
    php artisan storage:link --force 2>/dev/null || true
    php artisan migrate --force
fi

exec "$@"
