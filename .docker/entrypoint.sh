#!/bin/bash
set -e

IS_WORKER=false
[[ "$*" == *"queue"* ]] && IS_WORKER=true

if [ "$IS_WORKER" = false ]; then
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

    # Generate APP_KEY once, written into the shared .env
    php artisan key:generate --force
else
    # Worker waits for app to create and populate .env
    echo "Waiting for .env..."
    until [ -f .env ]; do sleep 1; done
fi

# Restore Vite build assets if the bind-mount shadowed them
if [ ! -f public/build/manifest.json ]; then
    mkdir -p public/build
    cp -r /build-assets/. public/build/
fi

# Recreate storage directories after volume mount (volume replaces Dockerfile-created dirs)
mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Register package manifests (deferred from build time)
php artisan package:discover --ansi

# Wait for MySQL to accept connections
echo "Waiting for database..."
until php artisan db:show --json > /dev/null 2>&1; do
    sleep 2
done
echo "Database is ready."

# Only the app service runs migrations and storage setup (not the queue worker)
if [ "$IS_WORKER" = false ]; then
    php artisan storage:link --force 2>/dev/null || true
    php artisan migrate --force
fi

exec "$@"
