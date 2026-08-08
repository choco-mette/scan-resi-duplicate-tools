#!/bin/sh

# Pastikan file database.sqlite ada dan miliki permissions yang benar
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi
chown www-data:www-data /var/www/html/database/database.sqlite
chmod 664 /var/www/html/database/database.sqlite

# Migrasi otomatis (force untuk production)
php artisan migrate --force

# Optimisasi konfigurasi (opsional)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Jalankan PHP-FPM di background
php-fpm -D

# Jalankan Nginx di foreground agar container tetap hidup
nginx -g 'daemon off;'
