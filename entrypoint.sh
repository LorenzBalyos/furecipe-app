#!/bin/sh

# Clear any cached deployment optimization files
php artisan config:clear
php artisan cache:clear

# Ensure database structure is up to date
php artisan migrate --force

# Force absolute read/write ownership permissions for the web server user
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Start the web server normally
exec apache2-foreground
