# Stage 1: Build Vite assets
FROM node:20-slim AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2: PHP application
FROM php:8.4-cli

WORKDIR /var/www/html

# System dependencies for PHP extensions
RUN apt-get update && apt-get install -y --no-install-recommends \
    libpng-dev \
    libzip-dev \
    libonig-dev \
    libxml2-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        zip \
        xml \
        bcmath \
        gd \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copy application source
COPY . .

# Store Vite-built assets outside the bind-mount root so the entrypoint can
# restore them after the host volume shadows public/build at runtime.
COPY --from=node-builder /app/public/build /build-assets
RUN rm -f public/hot

# Install PHP dependencies without running post-install artisan scripts
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress --no-scripts

# Ensure writable directories exist
RUN mkdir -p storage/framework/{sessions,views,cache} \
             storage/logs \
             bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy and register entrypoint
COPY .docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8000

ENTRYPOINT ["/entrypoint.sh"]
