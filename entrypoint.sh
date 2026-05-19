#!/bin/sh

# Safely clear the internal caches at boot time
php artisan config:clear
php artisan cache:clear

# Run database migrations
php artisan migrate --force

# Start apache web server normally
exec apache2-foreground
