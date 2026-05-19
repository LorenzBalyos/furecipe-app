#!/bin/sh

# Clear deployment caches
php artisan config:clear
php artisan cache:clear

# Ensure the storage directory structure exists safely
mkdir -p /var/www/html/storage/app

# If Render has the Firebase JSON string, write it into the file dynamically
if [ -n "$FIREBASE_JSON" ]; then
    echo "$FIREBASE_JSON" > /var/www/html/storage/app/firebase_credentials.json
fi

# Run database migrations
php artisan migrate --force

# Fix folder ownership permissions so Laravel can read/write logs and session states
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Start the web server process
exec apache2-foreground
