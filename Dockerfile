# Stage 1: Build dependencies (Multi-stage build)
FROM composer:latest as build
WORKDIR /app
COPY . .
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs

# Stage 2: Production image (Sangat ringan dengan Alpine)
FROM php:8.3-fpm-alpine

# Install Nginx dan dependensi Alpine
RUN apk add --no-cache \
    nginx \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    sqlite \
    sqlite-dev \
    libzip-dev \
    icu-dev \
    # Build ekstensi PHP
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_sqlite pcntl opcache zip intl bcmath exif \
    # Hapus paket -dev yang memakan ruang setelah kompilasi
    && apk del sqlite-dev libpng-dev libjpeg-turbo-dev freetype-dev libzip-dev icu-dev \
    # Install ulang versi runtime agar program bisa jalan tanpa file dev
    && apk add --no-cache libpng libjpeg-turbo freetype libzip icu-libs

WORKDIR /var/www/html

# Copy hasil build dari stage 1
COPY --from=build /app /var/www/html

# Setup Nginx
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Eksekusi entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port 80 untuk Nginx
EXPOSE 80

ENTRYPOINT ["docker-entrypoint.sh"]
